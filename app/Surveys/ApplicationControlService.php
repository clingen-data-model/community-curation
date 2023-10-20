<?php

namespace App\Surveys;

use Illuminate\Support\Str;
use Sirs\Surveys\Contracts\SurveyModel;
use Sirs\Surveys\Contracts\SurveyResponse;
use Sirs\Surveys\SurveyControlService;

class ApplicationControlService extends SurveyControlService
{
    public static function generateSurveyUrl(SurveyModel $survey, SurveyResponse $response)
    {
        $urlParts = [
            'apply',
            $response->id,
        ];

        return implode('/', $urlParts);
    }

    /**
     * Aliases accessors  and mutators.
     *
     * @return mixed
     **/
    public function __get($attr)
    {
        if (isset($this->{$attr})) {
            return $this->{$attr};
        } elseif (method_exists($this, 'get'.Str::camel($attr).'Attribute')) {
            $methodName = Str::camel('get_'.$attr.'Attribute');

            return $this->$methodName();
        }
    }
}
