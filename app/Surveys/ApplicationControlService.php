<?php

namespace App\Surveys;

use Sirs\Surveys\SurveyControlService;
use Sirs\Surveys\Contracts\SurveyModel;
use Sirs\Surveys\Contracts\SurveyResponse;

class ApplicationControlService extends SurveyControlService
{
    public static function generateSurveyUrl(SurveyModel $survey, SurveyResponse $response)
    {
        $urlParts = [
            'apply',
            $response->id
        ];
        return implode('/', $urlParts);
    }    

    /**
     * Aliases accessors  and mutators
     *
     * @return mixed
     **/
    public function __get($attr)
    {
        if (isset($this->{$attr})) {
            return $this->{$attr};
        } elseif (method_exists($this, 'get'.camel_case($attr).'Attribute')) {
            $methodName = camel_case('get_'.$attr.'Attribute');
            return $this->$methodName();
        }
    }

}
