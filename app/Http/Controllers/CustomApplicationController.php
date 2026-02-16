<?php

namespace App\Http\Controllers;

use App\CustomSurvey;
use Illuminate\Http\Request;

class CustomApplicationController extends Controller
{
    public function show(Request $request, $name)
    {
        $customSurvey = CustomSurvey::findByNameOrFail($name);

        $prefillData = [
            'custom_survey_id' => $customSurvey->id,
            'curation_activity_1' => $customSurvey->curationGroup->curationActivity->id,
            'panel_1' => $customSurvey->curation_group_id,
            'volunteer_type' => $customSurvey->volunteer_type_id,
        ];

        return view('surveys.take', [
            'slug' => 'application1',
            'redirectUrl' => '/apply/group/thank-you',
            'prefillData' => $prefillData,
        ]);
    }

    public function store(Request $request, $name, $id = null)
    {
        // API handles save now via SurveyController
        return redirect('/apply/group/thank-you');
    }
}
