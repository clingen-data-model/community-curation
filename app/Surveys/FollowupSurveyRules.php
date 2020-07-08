<?php
namespace App\Surveys;

use App\Surveys\SurveyRules;

class FollowupSurveyRules extends SurveyRules
{
    public function getRedirectUrl()
    {
        if (!session()->get('survey-previous')) {
            return '/';
        }
        return null;
    }
}
