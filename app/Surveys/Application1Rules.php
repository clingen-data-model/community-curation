<?php

namespace App\Surveys;

use App\CurationGroup;
use Illuminate\Support\Facades\Auth;

class Application1Rules extends SurveyRules
{
    const VOLUNTEER_TYPE_COMPREHENSIVE = 2;
    const VOLUNTEER_TYPE_BASELINE = 1;

    public function getRedirectUrl()
    {
        if (Auth::guest()) {
            return '/apply/thank-you';
        }

        return null;
    }

    public function prioritiesSkip()
    {
        if ($this->response->volunteer_type == static::VOLUNTEER_TYPE_BASELINE) {
            return 2;
        }

        if ($this->response->custom_survey_id) {
            return 2;
        }

        return 0;
    }

    public function commitmentSkip()
    {
        if ($this->response->custom_survey_id) {
            return 2;
        }
        return 0;
    }
}
