<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ImportInitialData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import existing data from google sheet';

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
    public function handle()
    {
        $assignmentsSheet = base_path('import_files/cc-volunteers.xlsx');
        $reader = ReaderEntityFactory::createReaderFromFile($assignmentsSheet);

        $reader->open($assignmentsSheet);


        foreach ($reader->getSheetIterator() as $sheet) {
            dump(get_class($sheet));
        }

        $reader->close();
    }

    private function createVolunteer($row):User
    {
        $nameParts = explode(' ',$row['name']);

        $user = User::firstOrCreate([
                'email' => $row['email address'],
            ],
            [
                'first_name' => array_shift($nameParts),
                'last_name' => implode(' ', $nameParts),
                'volunteer_type_id' => $this->getVolunteerTypeId($row['Volunteer Type']),
            ]);
        $user->assignRole('volunteer');
        return $user;
    }

    private function transcribeApplication(User $volunteer, $row)
    {
        if ($volunteer->application) {
            $this->setAdditionalPriorities($volunter, $row);
            return;
        }

        $nameParts = explode(' ', $row['name']);
        $response = class_survey()::findBySlug('application1')->getNewResponse($volunteer);
        $response->fill([
            'first_name' => array_shift($nameParts),
            'last_name' => implode(' ', $nameParts)
        ]);

    }
    
    private function setAdditionalPriorities(User $volunter, $row)
    {
        //code
    }
    

    private function getVolunteerTypeId($typeString)
    {
        if ($typeString == 'Baseline') {
            return 1;
        }
        if ($typeString == 'Comprehensive') {
            return 2;
        }

        throw new \Exception('Unkown volunteer type string: '.$typeString);
    }
    
    
}
