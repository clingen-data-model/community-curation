<?php

namespace App\Surveys\Workflows;

use App\Application;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationCompletedMail;
use App\Traits\StoresResponsePriorities;
use App\Actions\Reports\ApplicationReportRowCreate;
use App\Jobs\CreateVolunteerFromApplication;
use Sirs\Surveys\SurveyResponseWorkflowStrategy;

/**
 * Extends SurveyResponseWorkflowStrategy (implements SurveyTypeWorkflowStrategyInterface).
 * You can override the following public methods:
 *     run()
 *     attended()
 *     canceled()
 *     missed()
 *     scheduled()
 *     wasRescheduled()
 *     statusWasUpdated()
 * Instance vars include $this->response and $this->event.
 *
 **/
class Application1WorkflowStrategy extends SurveyResponseWorkflowStrategy
{
    use StoresResponsePriorities;

    public function finalizing()
    {
        if (is_null($this->response->respondent)) {
            CreateVolunteerFromApplication::dispatch($this->response);
        }

        $this->storePriorities();

        session()->forget('application-response');
    }

    public function finalized()
    {
        Mail::to($this->response->email)->send(new ApplicationCompletedMail($this->response));
        (new ApplicationReportRowCreate)->handle(Application::find($this->response->id));
    }
} // END class SurveyResponseTypeWorkflowStrategy
