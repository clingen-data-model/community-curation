<?php

namespace App\Surveys\Workflows;

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
        if (is_null($this->respondent)) {
                $volunteer = User::create([
                'name' => $this->response->applicant_name,
                'email' => $this->response->email,
                'password' => \Hash::make(uniqid())
            ]);
            $volunteer->assignRole('volunteer');
        }
    }
} // END class SurveyResponseTypeWorkflowStrategy

?>