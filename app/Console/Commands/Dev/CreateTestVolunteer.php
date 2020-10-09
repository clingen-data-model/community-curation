<?php

namespace App\Console\Commands\Dev;

use App\Country;
use App\CurationActivity;
use App\Survey;
use Illuminate\Console\Command;

class CreateTestVolunteer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:make:volunteer {--type= : volunteer-type (baseline, comprehensive)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(\Faker\Generator $faker)
    {
        $this->faker = $faker;
        $response = $this->makeResponse();
        $response->save();
        $response->finalize();
    }

    private function makeResponse()
    {
        $response = Survey::where('slug', 'application1')->first()->getNewResponse(null);
        $response->applicant_name = $this->faker->name;
        $response->institution = $this->faker->company;
        $response->street1 = $this->faker->streetName;
        $response->street2 = null;
        $response->city = $this->faker->city;
        $response->state = $this->faker->state;
        $response->country_id = $this->randomModelId(Country::class);
        $response->zip = $this->faker->word;
        $response->email = $this->faker->email;
        $response->timezone = 1;
        $response->highest_ed = rand(1, 6);
        $response->ad_campaign = json_encode([1, 2]);
        $response->self_desc = rand(1, 2);
        $response->motivation = json_encode([1, 2]);
        $response->goals = json_encode([1, 2]);
        $response->interests = json_encode([1, 2]);
        $response->volunteer_type = $this->getVolunteerType();

        if ($response->volunteer_type == 2) {
            $response->curation_activity_1 = $this->randomModelId(CurationActivity::class);
            $response->panel_1 = CurationActivity::find(1)->curationGroups->random()->id;
            $response->effort_experience_1 = rand(0, 1);
            $response->activity_experience_1 = rand(0, 1);

            $response->curation_activity_2 = $this->randomModelId(CurationActivity::class);
            $response->panel_2 = CurationActivity::find(1)->curationGroups->random()->id;
            $response->effort_experience_2 = rand(0, 1);
            $response->activity_experience_2 = rand(0, 1);

            $response->curation_activity_3 = $this->randomModelId(CurationActivity::class);
            $response->panel_3 = CurationActivity::find(1)->curationGroups->random()->id;
            $response->effort_experience_3 = rand(0, 1);
            $response->activity_experience_3 = rand(0, 1);
        }

        return $response;
    }

    private function randomModelId($class)
    {
        return $class::select('id')->get()->random()->id;
    }

    private function getVolunteerType()
    {
        if ($this->option('type')) {
            return ($this->option('type') == 'baseline') ? 1 : 2;
        }

        return rand(1, 2);
    }
}
