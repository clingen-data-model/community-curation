<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SurveyByIdController extends Controller
{
    public function show(Request $request, $surveyId, $responseId): RedirectResponse
    {
        $surveySlug = class_survey()::find($surveyId)->slug;

        return redirect(route('surveys.responses.show', [$surveySlug, $responseId]));
    }
}
