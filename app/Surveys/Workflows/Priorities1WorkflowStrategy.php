<?php

namespace App\Surveys\Workflows;

use App\Traits\StoresResponsePriorities;
use Sirs\Surveys\SurveyResponseWorkflowStrategy;

class Priorities1WorkflowStrategy extends SurveyResponseWorkflowStrategy
{
    use StoresResponsePriorities;

    public function finalizing()
    {
        $this->storePriorities();
    }
} // END class SurveyResponseTypeWorkflowStrategy

?>