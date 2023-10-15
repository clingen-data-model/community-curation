<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Sirs\Surveys\Contracts\SurveyResponse;

class ApplicationCompletedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $response;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SurveyResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        $mailable = $this->subject('ClinGen - Completed Volunteer Survey')
            ->view('email.application_completed')
            ->with([
                'name' => $this->response->first_name.' '.$this->response->last_name,
            ]);

        return $mailable;
    }
}
