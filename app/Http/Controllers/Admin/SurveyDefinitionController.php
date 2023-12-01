<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SurveyDefinitionController extends Controller
{
    public function index()
    {
        $surveys = class_survey()::all();

        return $surveys->map(function ($svy) {
            return url('/admin/survey-definitions/'.$svy->slug);
        });
    }

    public function show($slug)
    {
        $survey = class_survey()::findBySlug($slug);

        $questions = collect($survey->document->questions)
                        ->map(function ($q) {
                            $data = [
                                'question' => trim(preg_replace('/\n                /', '', $q->questionText)),
                                'name' => $q->variableName,
                                'data-type' => $q->dataFormat,
                                'required' => $q->required,
                                'validationRules' => $q->validationRules,
                            ];

                            if ($q->hasOptions()) {
                                $data['options'] = $q->options->map(function ($opt) {
                                    return $opt->label;
                                });
                                $data['single/multiple'] = $q->numSelectable > 1 ? 'multiple' : 'single';
                            }

                            return $data;
                        });

        return $questions;
    }
}
