<?php

namespace App\Jobs;

use App\User;
use App\SurveyResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateVolunteerFromApplication
{
    use Dispatchable, Queueable;

    protected $response;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SurveyResponse $response)
    {
        //
        $this->response = $response;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::create([
            'first_name' => $this->response->first_name,
            'last_name' => $this->response->last_name,
            'email' => $this->response->email,
            'orcid_id' => $this->response->orcid_id,
            'institution' => $this->response->institution,
            'password' => Hash::make(uniqid()),
            'street1' => $this->response->street1,
            'street2' => $this->response->street2,
            'city' => $this->response->city,
            'state' => $this->response->state,
            'zip' => $this->response->zip,
            'country_id' => $this->response->country_id,
            'volunteer_type_id' => $this->response->volunteer_type,
            'volunteer_status_id' => 1,
            'hypothesis_id' => $this->response->hypothesis_id,
            'timezone' => $this->response->timezone
        ]);
        $user->assignRole('volunteer');
        $this->response->respondent_type = User::class;
        $this->response->respondent_id = $user->id;
    }
}
