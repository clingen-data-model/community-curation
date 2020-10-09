<?php

namespace App\Http\Controllers;

use App\Surveys\ApplicationControlService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = $this->getResponseObject($request);

        $service = new ApplicationControlService($request, $response);

        return $service->showPage();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id = null)
    {
        $survey = class_survey()::findBySlug('application1');
        $survey->getSurveyDocument()->validate();

        $response = $this->getResponseObject($request, $id);
        $control = new ApplicationControlService($request, $response);

        return $control->saveAndContinue();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id = null)
    {
        $response = $this->getResponseObject($request, $id);

        $service = new ApplicationControlService($request, $response);

        return $service->showPage();
    }

    private function getResponseObject(Request $request, $id = null)
    {
        $survey = class_survey()::findBySlug('application1');
        $sessionResponse = $request->session()->get('application-response');

        if (!is_null($id)) {
            if (Auth::guest() && (is_null($sessionResponse) || $sessionResponse->id != $id)) {
                throw new AuthorizationException('You don\'t have permission to access this survey response');
            }

            $response = $survey->responses()->findOrFail($id);

            if (Auth::user() && Auth::user()->id != $response->respondent_id && !Auth::user()->hasAnyRole(['programmer', 'super-admin', 'admin'])) {
                throw new AuthorizationException('You don\'t have permission to access this survey response');
            }

            $request->session()->put('application-response', $response);

            return $response;
        }

        $response = $request->session()->get('application-response', null);
        if (!is_null($response) && $response->id && $survey->responses()->find($response->id)) {
            return $response;
        }

        $response = $survey->getNewResponse(null);
        session()->put('application-response', $response);

        return $response;
    }
}
