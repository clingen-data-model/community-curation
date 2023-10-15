<?php

namespace App\Http\Controllers;

use App\Surveys\ApplicationControlService;
use App\Surveys\ResponseObjectResolver;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    protected $responseResolver;

    public function __construct(ResponseObjectResolver $responseResolver)
    {
        $this->responseResolver = $responseResolver;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = $this->responseResolver->resolve($request);

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

        $response = $this->responseResolver->resolve($request, $id);
        $control = new ApplicationControlService($request, $response);

        return $control->saveAndContinue();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id = null)
    {
        $response = $this->responseResolver->resolve($request, $id);

        $service = new ApplicationControlService($request, $response);

        return $service->showPage();
    }
}
