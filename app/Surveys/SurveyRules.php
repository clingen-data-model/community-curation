<?php

namespace App\Surveys;

use Sirs\Surveys\SurveyRules as BaseSurveyRules;

class SurveyRules extends BaseSurveyRules
{
    public function beforeShow()
    {
        // $context = parent::beforeShow();
        $context = [
            'hideSaveExit' => true,
            'hideSave' => true,
        ];

        return $context;
    }
}
