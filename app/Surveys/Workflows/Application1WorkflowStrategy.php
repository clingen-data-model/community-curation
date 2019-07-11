<?php

namespace App\Surveys\Workflows;

use App\User;
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
    public function finalized()
    {
        if (is_null($this->response->respondent)) {
            $volunteer = User::create([
                'name' => $this->response->applicant_name,
                'email' => $this->response->email,
                'password' => \Hash::make(uniqid())
            ]);
            $volunteer->assignRole('volunteer');
        }
        session()->forget('application-survey');
    }
} // END class SurveyResponseTypeWorkflowStrategy

?>