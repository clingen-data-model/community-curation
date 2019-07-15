<?php
namespace App\Surveys;

use Sirs\Surveys\SurveyRules;

class Application1Rules extends SurveyRules
{
    const VOLUNTEER_TYPE_COMPREHENSIVE = 2;
    const VOLUNTEER_TYPE_BASELINE = 1;

    public function getRedirectUrl()
    {
        if ($this->response->volunteer_type == static::VOLUNTEER_TYPE_COMPREHENSIVE) {
            return url('/app-user/'.$this->response->respondent_id.'/survey/priorities1/new');
        }

        if ($this->response->volunteer_type == static::VOLUNTEER_TYPE_BASELINE) {
            return url('/apply/thank-you');
        }

        return null;
    }
}
