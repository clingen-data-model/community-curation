<?php

namespace App\Http\Controllers;

use App\CustomSurvey;
use App\Surveys\ApplicationControlService;
use App\Surveys\ResponseObjectResolver;
use Illuminate\Http\Request;

class CustomApplicationController extends Controller
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
    public function index(Request $request, $name)
    {
        $customSurvey = CustomSurvey::findByNameOrFail($name);
        $response = $this->responseResolver->resolve($request);
        $response->custom_survey_id = $customSurvey->id;

        $service = new ApplicationControlService($request, $response);

        return $service->showPage();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $name, $id = null)
    {
        $customSurvey = CustomSurvey::findByNameOrFail($name);
        $survey = class_survey()::findBySlug('application1')->getSurveyDocument()->validate();

        $response = $this->responseResolver->resolve($request, $id);

        // Set the custom survey data on the response
        $response->custom_survey_id = $customSurvey->id;
        $response->curation_activity_1 = $customSurvey->curationGroup->curationActivity->id;
        $response->panel_1 = $customSurvey->curation_group_id;
        $response->volunteer_type = $customSurvey->volunteer_type_id;

        $control = new ApplicationControlService($request, $response);

        return $control->saveAndContinue();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $name, int $id = null)
    {
        $customSurvey = CustomSurvey::findByNameOrFail($name);
        $response = $this->responseResolver->resolve($request, $id);
        $response->custom_survey_id = $customSurvey->id;

        $service = new ApplicationControlService($request, $response);

        return $service->showPage();
    }
}
