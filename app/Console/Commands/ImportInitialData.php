<?php

namespace App\Console\Commands;

use App\User;
use Exception;
use Throwable;
use App\Aptitude;
use App\Priority;
use App\Training;
use Carbon\Carbon;
use App\Assignment;
use App\Application;
use App\Attestation;
use App\CurationGroup;
use App\UserAptitude;
use App\CurationActivity;
use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use App\Import\Maps\CurationGroupMap;
use App\Jobs\AssignVolunteerToAssignable;
use App\Import\Exceptions\ImportException;
use App\Exceptions\InvalidAssignmentException;
use App\Import\SheetHandlers\GeneAttestationHandler;
use App\Import\SheetHandlers\AssignmentsSheetHandler;
use App\Import\SheetHandlers\ApplicationSurveyHandler;
use App\Import\SheetHandlers\DosageAttestationHandler;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use App\Import\SheetHandlers\VariantAttestationSheetHandler;
use App\Import\SheetHandlers\ActionabilityAttestationHandler;

/**
 * Command that imports initial data from spreadsheet
 *
 * @SuppressWarnings(PHPMD)
 */
class ImportInitialData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:all {--enable-info : enable info output }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import existing data from google sheet';

    /**
     * @var CurationGroupMap mapper for curation groups
     */
    private $curationGroupMap;
    
    /**
     * @var Collection Collection of CurationActivity models
     */
    private $curationActivities;
    

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CurationGroupMap $curationGroupMap)
    {
        $this->clearAllUsers();
        if (!app()->environment('production')) {
            $this->info('Setting mail driver to log');
            config(['mail.driver' => 'log']);
        }

        $this->curationGroupMap = $curationGroupMap;

        config(['mail.driver' => 'log']);
        $this->curationGroups = CurationGroup::all();
        $this->curationActivities = CurationActivity::all();


        $assignmentsSheet = base_path('import_files/cc-volunteers.xlsx');
        $reader = ReaderEntityFactory::createReaderFromFile($assignmentsSheet);

        $reader->open($assignmentsSheet);

        $handlerChain = $this->buildHandlerChain();

        $volunteerRows = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            $rows = $handlerChain->handle($sheet);
            foreach ($rows as $email => $data) {
                $email = strtolower($email);
                if (!isset($volunteerRows[$email])) {
                    $volunteerRows[$email] = collect();
                }
                $volunteerRows[$email][$sheet->getName()] = $data;
            }
        }
        $reader->close();

        $volunteerCollection = collect($volunteerRows)->filter(function ($val, $key) {
            return $key != ""
                && !in_array($key, [
                    'Emma Wilcox',
                    'Revathi Rajkumar',
                    'Rajiv Machado',
                    'Carrie Welch',
                    'Laura Southgate',
                    'Micheala Aldred',
                    'Britt Johnson',
                    'Jair Tenorio',
                    'Divya Pandya',
                    'Emilia Swietlik',
                    'Christina A. Eichstaedt',
                    'Madeline Hughes',
                    '1',
                    'Krzysztof SzczaÅ‚uba'
                ]);
        });

        $nameToEmailAddress = $this->getNameToEmailMap($volunteerCollection);

        $volunteerCollection->filter(function ($val, $key) {
            return !looksLikeEmailAddress($key);
        })
            ->sortKeys()
            ->each(function ($attestationData, $nameKey) use ($volunteerCollection, $nameToEmailAddress) {
                if ($volunteerCollection->keys()->contains($nameKey)) {
                    $email = $nameToEmailAddress->get(mb_strtolower($nameKey));
                    if (!$volunteerCollection->get($email)) {
                        $this->warn('We can not find an email address for name '.$nameKey);
                        return;
                    }
                    $volunteerCollection->put($email, $volunteerCollection->get($email)->merge($attestationData));
                }
            });

        $volunteerCollection = $volunteerCollection
            ->filter(function ($val, $key) {
                return strstr($key, '@') && $key != "" && is_string($key);
            });

        $bar = $this->output->createProgressBar($volunteerCollection->count());
        $volunteerCollection->each(function ($volunteerData, $key) use ($bar) {
            $this->processVolunteerData($volunteerData, $key);
            $bar->advance();
        });
        echo "\n";

        if (!app()->environment('production')) {
            $this->dummifyEmails();
        }
    }

    private function dummifyEmails()
    {
        $this->info('Dummify emails for non-prod environments');
        $volunteers = User::isVolunteer()->get();
        $bar = $this->output->createProgressBar($volunteers->count());
        $volunteers->each(function ($u) use ($bar) {
            list($name, $domain) = explode('@', $u->email);
            try {
                $u->update(['email' => $name.'@example.com']);
            } catch (\Exception $e) {
                $u->update(['email' => $name.'1@example.com']);
            }
            $bar->advance();
        });
        $bar->finish();
        echo "\n";
    }

    private function processVolunteerData($volunteerData, $email)
    {
        $this->clearExistingUser($email);
        
        try {
            $this->outputInfo("\n");
            $this->outputInfo('importing data for '.$email);
            $response = $this->createSurveyResponse($volunteerData);
            $volunteer = $response->respondent;
            $this->importVolunteerAssignments($volunteer, $volunteerData);
            $this->updateVolunteerStatus($volunteer, $volunteerData);
        } catch (ImportException $th) {
            $this->warn(
                // str_repeat('-', strlen($th->getMessage()))."\n"
                // .
                $th->getMessage()
                // . "\n".str_repeat('-', strlen($th->getMessage()))
            );
        }
    }

    private function buildHandlerChain()
    {
        $assignmentsSheetHandler = new AssignmentsSheetHandler();
        $applicationHandler = new ApplicationSurveyHandler();
        $geneAttestationHandler = new GeneAttestationHandler();
        $dosageAttestationHandler = new DosageAttestationHandler();
        $actionabilityAttestationHandler = new ActionabilityAttestationHandler();
        $variantAttestationHandler = new VariantAttestationSheetHandler();
        
        $assignmentsSheetHandler
            ->setNext($applicationHandler)
            ->setNext($geneAttestationHandler)
            ->setNext($actionabilityAttestationHandler)
            ->setNext($dosageAttestationHandler)
            ->setNext($variantAttestationHandler);

        return $assignmentsSheetHandler;
    }

    private function clearAllUsers()
    {
        $this->info('Deleting Attestations');
        Attestation::withTrashed()->get()->each(function ($item) {
            $item->forceDelete();
        });
        $this->info('Deleting Assignments');
        Assignment::hasParent()->withTrashed()->get()->each->forceDelete();
        Assignment::withTrashed()->get()->each->forceDelete();
        $this->info('Deleting UserAptitudes');
        UserAptitude::withTrashed()->get()->each->forceDelete();
        $this->info('Deleting Priorities');
        Priority::all()->each->forceDelete();
        $this->info('Deleting Applications');
        Application::all()->each->forceDelete();
        $this->info('Deleting Volunteers');
        User::isVolunteer()->withTrashed()->get()->each->forceDelete();
    }

    private function clearExistingUser($email)
    {
        $user = $this->findUserByEmail($email);
        if ($user) {
            $user->priorities->each->forceDelete();
            if ($user->application) {
                $user->application->forceDelete();
            }
            class_survey()::findBySlug('application1')
                ->responses()
                ->where('respondent_id', $user->id)
                ->get()
                ->each
                ->forceDelete();

            Attestation::where('user_id', $user->id)->get()->each->forceDelete();
            UserAptitude::where('user_id', $user->id)->get()->each->forceDelete();
            $user->assignments->each->forceDelete();
            $user->ForceDelete();
        }
    }

    private function findUserByEmail($email)
    {
        $altEmail = $email;
        if (!app()->environment('production')) {
            list($name, $host) = explode('@', $email);
            $email = $name.'@example.com';
            $altEmail = $name.'1@example.com';
        }
        $user = User::where('email', $email)->orWhere('email', $altEmail)->first();
        return $user;
    }

    private function createSurveyResponse($volunteerData)
    {
        if (!$volunteerData->keys()->contains('Volunteer Survey')) {
            throw new ImportException($volunteerData->first()['email'] . ' does not appear to have a volunteer survey.');
        }

        
        $lastRecord = collect($volunteerData->get('Volunteer Survey'))->last();
        $this->outputInfo(' - Application data.');
        unset($lastRecord['name']);

        $response = class_survey()::findBySlug('application1')->getNewResponse(null);
        $response->fill($lastRecord);
        $response->created_at = $lastRecord['created_at'];
        $response->updated_at = $lastRecord['created_at'];
        $response->save();
        $response->finalize(Carbon::parse($lastRecord['created_at']));
        $response = $response->fresh();

        
        return $response;
    }
        
    private function importVolunteerAssignments($volunteer, $volunteerData)
    {
        if (!$volunteerData->keys()->contains('Assignments')) {
            throw new ImportException('Missing Assignments data for '. $volunteer->email);
        }
        
        $assignmentData = collect($volunteerData->get('Assignments'));
        $attestationData = [
            1 => $volunteerData->get('Actionability Attestations') ? collect($volunteerData->get('Actionability Attestations')) : null,
            2 => $volunteerData->get('Dosage Attestations') ? collect($volunteerData->get('Dosage Attestations')) : null,
            3 => $volunteerData->get('Gene Attestations') ? collect($volunteerData->get('Gene Attestations')) : null,
            4 => null,
            5 => $volunteerData->get('Variant Attestations') ? collect($volunteerData->get('Variant Attestations')) : null,
        ];

        $assignmentData->each(function ($data) use ($volunteer, $attestationData) {
            if (empty($data['ca_assignment'])) {
                return;
            }
            $assignment = $this->assignCurationActivity($volunteer, $data);
            if (!$assignment) {
                return;
            }

            if (empty($data['training_date']) || !$data['training_attended']) {
                $this->outputInfo('    - training not complete');
                return;
            }
            $this->updateTraining($assignment, $data);

            if (!$data['attestation_signed']) {
                $this->outputInfo('    - attestation not signed');
                return;
            }
            $this->updateAttestation($assignment, $data, $attestationData);
                
            if (empty($data['ep_assignment'])) {
                $this->outputInfo('    - Not assigned to WG/EP'.' ('.$data['email'].')');
                return;
            }
            $this->assignCurationGroup($volunteer, $data);
        });
    }

    private function assignCurationActivity($volunteer, $data)
    {
        $ca = $this->curationActivities->firstWhere('legacy_name', $data['ca_assignment']);

        if ($ca == 'NA' || is_null($ca)) {
            return;
        }
        $this->outputInfo(' - Assignment data ['.$ca->name.']');

        if (!$ca) {
            throw new ImportException('CA Unknown: '.$data['ca_assignment'].' ('.$data['email'].')');
        }
        
        try {
            AssignVolunteerToAssignable::dispatchNow($volunteer, $ca);
        } catch (InvalidAssignmentException $th) {
            $volunteer->update(['volunteer_type_id' => config('volunteers.types.comprehensive')]);
            AssignVolunteerToAssignable::dispatchNow($volunteer, $ca);
        }

        $assignment = $volunteer->assignments()
            ->with('userAptitudes')
            ->assignableIs(get_class($ca), $ca->id)
            ->get()
            ->first();

        $assignment->created_at = $data['ca_assignment_date'];
        $assignment->updated_at = $data['ca_assignment_date'];
        $assignment->save();

        return $assignment;
    }
    
    private function updateTraining($assignment, $data)
    {
        try {
            $userAptitude = $assignment->userAptitudes()->first();
            $userAptitude->created_at = $data['ca_assignment_date'];
            $userAptitude->updated_at = $data['ca_assignment_date'];
            $userAptitude->save();
            
            $this->outputInfo('    - import training info');
            $userAptitude->trained_at = $data['training_date'];
            $userAptitude->updated_at = $data['training_date'];
            $userAptitude->save();
        } catch (Exception $e) {
            dump($e->getMessage());
        }
    }
    
    private function updateAttestation($assignment, $data, $attestationData)
    {
        $attestation = $assignment->attestations()->first();
        $attestation->created_at = $data['training_date'];
        $attestation->updated_at = $data['training_date'];
        $attestation->save();

        $this->outputInfo('    - import attestation info');
        $signedAt = Carbon::now();
        if ($attestationData[$assignment->assignable_id]) {
            $signedAt = $attestationData[$assignment->assignable_id]->get('signed_at');
            $attestation->signed_at = $signedAt;
            $attestation->data = $attestationData[$assignment->assignable_id]['data'];
            $attestation->save();
        }
    }
    
    private function getNameToEmailMap(Collection $collection)
    {
        return $collection
                ->filter(function ($val, $key) {
                    return strstr($key, '@');
                })
                ->transform(function ($item, $key) {
                    // if ($key == 'rodrigomendezh@gmail.com') {
                    //     return strtolower('Hector Rodrigo Méndez');
                    // }
                    $name = $item->get('Volunteer Survey')[count($item->get('Volunteer Survey'))-1]['name'];
                    if (is_null($name)) {
                        $this->warn('expect name in volunteer survey to be a string, '.gettype($name).' found for email address '.$key.'.');
                        return '';
                    }
                    return mb_strtolower($name);
                })
                ->flip();
    }
    
    private function assignCurationGroup($volunteer, $data)
    {
        $curationGroup = null;
        try {
            $curationGroup = $this->curationGroupMap->map($data['ep_assignment']);
        } catch (ImportException $th) {
            if ($th->getCode() == 409) {
                $curationGroup = $this->curationGroupMap->mapAbiguous($data['ep_assignment'], $data['ca_assignment']);
            }
        }

        if (is_null($curationGroup)) {
            throw new ImportException('EP Uknown: '.$data['ep_assignment'].' ('.$data['email'].')');
        }

        try {
            $this->outputInfo('    - assign curation group ['.$curationGroup->name.']');
            AssignVolunteerToAssignable::dispatch($volunteer, $curationGroup);
        } catch (InvalidArgumentException $th) {
            throw new ImportException($th->getMessage());
        }
    }

    private function updateVolunteerStatus($volunteer, $data)
    {
        $assignmentsData = $data->get('Assignments')[0];
        $statusString = trim(strtolower($assignmentsData['status']));
        $statuses = [
            'applied' => 1,
            'trained' => 2,
            'active' => 3,
            'unresponsive' => 4,
            'declined' => 5,
            'retired' => 6,
            'follow up email' => 1,
            'recontact later' => 1
        ];

        if (array_key_exists($statusString, $statuses)) {
            $volunteer->update([
                'volunteer_status_id' => $statuses[$statusString]
            ]);
            return;
        }

        if (in_array($statusString, ['contacted', 'assigned'])) {
            $statusId = $statuses['applied'];
            if ($assignmentsData['training_date']) {
                $statusId = $statuses['trained'];
            }
            if ($volunteer->assignments()->curationGroup()->first()) {
                $statusId = $statuses['active'];
            }
            $volunteer->update([
                'volunteer_status_id' => $statusId
            ]);
            return;
        }

        if (in_array($statusString, ['unassigned', 'folow up email'])) {
            return;
        }

        throw new ImportException('Unknown status '.$statusString.' for '.$volunteer['email']);
    }

    private function outputInfo($message)
    {
        if ($this->option('enable-info')) {
            $this->info($message);
        }
    }
}
