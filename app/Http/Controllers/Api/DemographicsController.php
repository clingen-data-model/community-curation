<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class DemographicsController extends Controller
{
    public function update(Request $request, $id)
    {
        $volunteer = User::findOrFail($id);

        $application = $volunteer->application;
        if (! $application) {
            $application = $volunteer->application()->create();
        }

        $survey = class_survey()::findBySlug('application1');
        $questionNames = array_keys($survey->document->getQuestions());

        foreach ($request->all() as $key => $value) {
            if (! in_array($key, $questionNames)) {
                abort(422, 'Variable '.$key.' not found in application definition');
            }
            if ($value !== null) {
                $application->{$key} = $value;
            }
        }

        if ($application->isDirty()) {
            $application->save();
        }
    }
}
