<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VolunteerFollowupController
{
    public function show(Request $request, $surveySlug, $responseId = null)
    {
        return view('surveys.take', [
            'slug' => $surveySlug,
            'redirectUrl' => '/',
        ]);
    }

    public function sixMonth()
    {
        return redirect('volunteer-followup/volunteer-six-month.1');
    }

    public function threeMonth()
    {
        return redirect('volunteer-followup/volunteer-three-month.1');
    }

}
