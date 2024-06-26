<?php

namespace App\Surveys;

use Illuminate\Support\Facades\Auth;

class Priorities1Rules extends SurveyRules
{
    public function getRedirectUrl()
    {
        if (Auth::guest()) {
            return '/apply/thank-you';
        }

        return null;
    }
}
