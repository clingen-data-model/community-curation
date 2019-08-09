<?php

namespace App\Surveys\Workflows;

use App\User;
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
    public function finalizing()
    {
        if (is_null($this->response->respondent)) {
            CreateVolunteerFromApplication::dispatch($this->response);
        }

        session()->forget('application-response');
    }
} // END class SurveyResponseTypeWorkflowStrategy
