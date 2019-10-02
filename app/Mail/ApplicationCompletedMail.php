<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Sirs\Surveys\Contracts\SurveyResponse;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicationCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

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
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.application_completed')
            ->with([
                'name' => $this->response->applicant_name,
            ]);
    }
}
