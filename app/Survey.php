<?php

namespace App;

use Sirs\Surveys\Contracts\SurveyModel;
use Sirs\Surveys\Models\Survey as SirsSurvey;

class Survey extends SirsSurvey implements SurveyModel
{
    public function getNewResponse($respondent)
    {
        $response = class_response()::newResponse($this->response_table);
        if ($respondent) {
            $response->respondent_type = $respondent::class;
            $response->respondent_id = $respondent->id;
        }
        $response->survey_id = $this->id;

        return $response;
    }
}
