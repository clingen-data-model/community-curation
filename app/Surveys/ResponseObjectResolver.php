<?php

namespace App\Surveys;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponseObjectResolver
{
    public function resolve(Request $request, $id = null)
    {
        $survey = class_survey()::findBySlug('application1');
        $sessionResponse = $request->session()->get('application-response');

        if (! is_null($id)) {
            if (Auth::guest() && (is_null($sessionResponse) || $sessionResponse->id != $id)) {
                throw new AuthorizationException('You don\'t have permission to access this survey response');
            }

            $response = $survey->responses()->findOrFail($id);

            if (Auth::user() && Auth::user()->id != $response->respondent_id && ! Auth::user()->hasAnyRole(['programmer', 'super-admin', 'admin'])) {
                throw new AuthorizationException('You don\'t have permission to access this survey response');
            }

            $request->session()->put('application-response', $response);

            return $response;
        }

        $response = $request->session()->get('application-response', null);
        if (! is_null($response) && $response->id && $survey->responses()->find($response->id)) {
            return $response;
        }

        $response = $survey->getNewResponse(null);
        session()->put('application-response', $response);

        return $response;
    }
}
