<?php

namespace App\Surveys;

class FollowupSurveyRules extends SurveyRules
{
    public function getRedirectUrl()
    {
        if (! session()->get('survey-previous')) {
            return '/';
        }

        return null;
    }
}
