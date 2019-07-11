<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Surveys\ApplicationControlService;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $survey = class_survey()::findBySlug('application1');
        $survey->getSurveyDocument()->validate();
        

        $response = $this->getResponseObject($request);
        $control = new ApplicationControlService($request, $response);
        return $control->saveAndContinue();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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

        if (!is_null($id)) {
            $response = $survey->responses()->findOrFail($id);
            $request->session()->put('application-response', $response);
            return $response;
        }

        return $request->session()->get('application-response', function () use ($survey) {
            $response = $survey->getNewResponse(null);
            session()->put('application-response', $response);
            return $response;
        });
    }
}
