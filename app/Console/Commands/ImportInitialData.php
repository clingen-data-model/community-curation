<?php

namespace App\Console\Commands;

use App\User;
use Throwable;
use App\Training;
use Carbon\Carbon;
use App\Attestation;
use App\ExpertPanel;
use App\CurationActivity;
use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use App\Jobs\AssignVolunteerToAssignable;
use App\Import\Exceptions\ImportException;
use App\Exceptions\InvalidAssignmentException;
use App\Import\Maps\ExpertPanelMap;
use App\Import\SheetHandlers\GeneAttestationHandler;
use App\Import\SheetHandlers\AssignmentsSheetHandler;
use App\Import\SheetHandlers\ApplicationSurveyHandler;
use App\Import\SheetHandlers\DosageAttestationHandler;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ImportInitialData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:all {--disable-info : disable info output }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import existing data from google sheet';

    /**
     * @var ExpertPanelMap mapper for expert panels
     */
     private $expertPanelMap;
    
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
    public function handle(ExpertPanelMap $expertPanelMap)
    {
        $this->expertPanelMap = $expertPanelMap;

        config(['mail.driver' => 'log']);
        $this->expertPanels = ExpertPanel::all();
        $this->curationActivities = CurationActivity::all();

        $assignmentsSheet = base_path('import_files/cc-volunteers.xlsx');
        $reader = ReaderEntityFactory::createReaderFromFile($assignmentsSheet);

        $reader->open($assignmentsSheet);

        $assignmentsSheetHandler = new AssignmentsSheetHandler();
        $applicationHandler = new ApplicationSurveyHandler();
        $geneAttestationHandler = new GeneAttestationHandler();
        $dosageAttestationHandler = new DosageAttestationHandler();
        
        $assignmentsSheetHandler->setNext($applicationHandler)
            ->setNext($geneAttestationHandler)
            ->setNext($dosageAttestationHandler);

        $volunteerRows = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            $rows = $assignmentsSheetHandler->handle($sheet);
            foreach ($rows as $email => $data) {
                if (!isset($volunteerRows[$email])) {
                    $volunteerRows[$email] = collect();
                }
                $volunteerRows[$email][$sheet->getName()] = $data;
            }
        }
        $reader->close();

        $volunteerCollection = collect($volunteerRows)->filter(function ($val, $key) { return $key != "";});

        $nameToEmailAddress = $this->getNameToEmailMap($volunteerCollection);
    
        $volunteerCollection->filter(function ($val, $key) {
                return !strstr($key, '@') && $key != "" && is_string($key);
            })
            ->each(function ($attestationData, $key) use ($volunteerCollection, $nameToEmailAddress) {                
                if ($volunteerCollection->keys()->contains($key)) {
                    $email = $nameToEmailAddress->get($key);
                    if (!$volunteerCollection->get($email)) {
                        $this->warn('We can not find an email address for name '.$key);
                        return;
                    }
                    $volunteerCollection->put($email, $volunteerCollection->get($email)->merge($attestationData));
                }
            });

        $volunteerCollection
            ->filter(function ($val, $key) {
                return strstr($key, '@') && $key != "" && is_string($key);
            })
            ->each(function ($volunteerData, $key) {
                $this->processVolunteerData($volunteerData, $key);
            });

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
        } catch (ImportException $th) {
            $this->warn(
                // str_repeat('-', strlen($th->getMessage()))."\n"
                // . 
                $th->getMessage()
                // . "\n".str_repeat('-', strlen($th->getMessage()))
            );
        }
    }

    private function clearExistingUser($email)
    {
        $user = User::where('email', $email)->first();
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
            Training::where('user_id', $user->id)->get()->each->forceDelete();
            $user->assignments->each->forceDelete();
            $user->ForceDelete();
        }
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
        $response->save();
        $response->finalize();
        $response = $response->fresh();

        
        return $response;
    }
        
    private function importVolunteerAssignments($volunteer, $volunteerData)
    {
        // dump($volunteerData->keys());
        if (!$volunteerData->keys()->contains('Assignments')) {
            throw new ImportException('Missing Assignments data for '. $volunteer->email);
        }
        
        $this->outputInfo(' - Assignment  data');
        $assignmentData = collect($volunteerData->get('Assignments'));
        $attestationData = [
            1 => null,
            2 => $volunteerData->get('Dosage Attestations') ? collect($volunteerData->get('Dosage Attestations')) : null,
            3 => $volunteerData->get('Gene Attestations') ? collect($volunteerData->get('Gene Attestations')) : null,
            4 => null,
            5 => null
        ];

        $assignmentData->each(function ($data) use ($volunteer, $attestationData) {
            if (!empty($data['ca_assignment'])) {
                $ca = $this->curationActivities->firstWhere('legacy_name', $data['ca_assignment']);

                if ($ca == 'NA' || is_null($ca)) {
                    return;
                }

                if (!$ca) {
                    throw new ImportException('CA Unknown: '.$data['ca_assignment'].' ('.$data['email'].')');
                }
                
                try {
                    AssignVolunteerToAssignable::dispatchNow($volunteer, $ca);
                } catch (InvalidAssignmentException $th) {
                    $volunteer->update(['volunteer_type_id' => config('volunteer_types.comprehensive')]);
                    AssignVolunteerToAssignable::dispatchNow($volunteer, $ca);
                }

                $assignment = $volunteer->assignments()
                    ->with('trainings')
                    ->assignableIs(get_class($ca), $ca->id)
                    ->get()
                    ->first();
                
                $assignment->created_at = $data['ca_assignment_date'];
                $assignment->updated_at = $data['ca_assignment_date'];
                $assignment->save();

                $training = $assignment->trainings->first();
                $training->created_at = $data['ca_assignment_date'];
                $training->updated_at = $data['ca_assignment_date'];
                $training->save();
                
                if (empty($data['training_date']) || !$data['training_attended']) {
                    $this->outputInfo('    - training not complete');
                    return;
                }
                $this->outputInfo('    - import training info');
                $training->completed_at = $data['training_date'];
                $training->updated_at = $data['training_date'];
                $training->save();

                $attestation = $assignment->attestations()->first();
                $attestation->created_at = $data['training_date'];
                $attestation->updated_at = $data['training_date'];
                $attestation->save();

                if (!$data['attestation_signed']) {
                    $this->outputInfo('    - attestation not signed');
                    return;
                }                
                $this->outputInfo('    - import attestation info');
                $signedAt = Carbon::now();
                if ($attestationData[$ca->id]) {
                    $signedAt = $attestationData[$ca->id]->get('signed_at');
                }
                $attestation->signed_at = $signedAt;
                $attestation->save();
                
                // dump($data);

                if (empty($data['ep_assignment'])) {
                    $this->outputInfo('    - Not assigned to WG/EP'.' ('.$data['email'].')');
                    return;
                }

                $expertPanel = $this->expertPanelMap->map($data['ep_assignment']);

                if (is_null($expertPanel)) {
                    throw new ImportException('EP Uknown: '.$data['ep_assignment'].' ('.$data['email'].')');
                }

                try {
                    AssignVolunteerToAssignable::dispatch($volunteer, $expertPanel);
                } catch (InvalidArgumentException $th) {
                    throw new ImportException($th->getMessage());
                }

            }
        });


    }

    private function getNameToEmailMap(Collection $collection)
    {
        return $collection
                ->filter(function ($val, $key) {
                    return strstr($key, '@');
                })
                ->transform(function ($item, $key) {
                    $name = $item->get('Volunteer Survey')[count($item->get('Volunteer Survey'))-1]['name'];
                    if (is_null($name)) {
                        $this->warn('expect name in volunteer survey to be a string, '.gettype($name).' found for email address '.$key.'.');
                        return '';
                    }
                    return $name;
                })
                ->flip();
    }
    
    private function outputInfo($message)
    {
        if (!$this->option('disable-info')) {
            $this->info($message);
        }
    }
    

}
