<?php 
namespace App\Surveys;

use Sirs\Surveys\SurveyRules;

class Priorities1Rules extends SurveyRules
{
    public function getRedirectUrl()
    {
        if (\Auth::guest()) {
            return '/apply/thank-you';
        }
        return null;
    }
    
}