<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Sirs\Surveys\SurveyControlService;

class VolunteerThreeMonthController
{
    public function show(Request $request, $responseId = null)
    {
        $this->setPreviousLocation($request);

        $respondent = Auth::user();
        $response = $this->getResponse($respondent, $responseId);

        if ($respondent->id == $response->respondent_id
            || Auth::user()->hasAnyRole(['programmer', 'admin'])) {
            $control = new SurveyControlService($request, $response);
    
            return $control->showPage();
        }

        throw new AuthorizationException('You do not have permission to view this survey response');
    }
    
    public function store(Request $request, $id = null)
    {
        $respondent = Auth::user();
        $response = $this->getResponse($respondent, $id);

        if (
            $respondent->id == $response->respondent_id
            || Auth::user()->hasAnyRole(['programmer', 'admin'])
        ) {
            if ($response->finalized_at) {
                throw new AuthorizationException('You can not update a finalized followup survey response');
            }

            $control = new SurveyControlService($request, $response);
    
            return $control->saveAndContinue();
        }

        throw new AuthorizationException('You do not have permission to update this survey response');
    }

    protected function getResponse($respondent, $responseId)
    {
        $survey = class_survey()::where('slug', 'volunteer-three-month1')->firstOrFail();
        if ($responseId) {
            return $survey->responses()->find($responseId);
        }

        return $survey->getLatestResponse($respondent);
    }
    
    protected function setPreviousLocation(Request $request)
    {
        $previous = $request->session()->pull('survey_previous');
        if (!preg_match('/\/survey\//', URL::previous())) {
            $previous = URL::previous();
        }
        $request->session()->put('survey_previous', $previous);
    }
}
