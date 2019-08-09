<?php

namespace App\Jobs;

use App\User;
use App\SurveyResponse;
use Illuminate\Bus\Queueable;
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
            'name' => $this->response->applicant_name,
            'email' => $this->response->email,
            'password' => \Hash::make(uniqid())
        ]);
        $user->assignRole('volunteer');
        $user->volunteer()->create([
            'address' => $this->response->address,
            'volunteer_type_id' => $this->response->volunteer_type
        ]);
        $this->response->respondent_type = User::class;
        $this->response->respondent_id = $user->id;
    }
}
