<?php

namespace App\Console\Commands\OneOff;

use App\Country;
use App\User;
use Illuminate\Console\Command;

class ImportVolunteerCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'one-off:countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mass assignment of countries based on spreadsheet';

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
        $text = file_get_contents(base_path('import_files/country_ingest.txt'), 'r');
        $lines = explode("\n", $text);
        array_shift($lines);
        $countries = Country::all()->keyBy('name');
        $bar = $this->output->createProgressBar(count($lines));
        $errors = [];
        foreach ($lines as $line) {
            [$email, $country] = explode("\t", $line);
            $user = User::findByEmail(trim($email));
            if (!$user) {
                array_push($errors, 'user with email: '.trim($email).' not found');
                $bar->advance();
                continue;
            }
            if (!empty($country)) {
                if (!isset($countries[$country])) {
                    array_push($errors, 'Country with name "'.$country.'" not found in country list');
                    $bar->advance();
                    continue;
                }
                $user->update(['country_id' => $countries[$country]->id]);
                $bar->advance();
            }
        }
        $bar->finish();echo("\n");
        
        foreach ($errors as $error) {
            $this->error($error);
        }
    }
}
