<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class VolunteerFollowupController
{
    public function show(Request $request, $surveySlug, $responseId = null)
    {
        $this->setPreviousLocation($request);

        $redirectUrl = $request->session()->get('survey_previous', '/');

        return view('surveys.take', [
            'slug' => $surveySlug,
            'redirectUrl' => $redirectUrl,
        ]);
    }

    public function sixMonth()
    {
        return redirect('volunteer-followup/volunteer-six-month1');
    }

    public function threeMonth()
    {
        return redirect('volunteer-followup/volunteer-three-month1');
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
