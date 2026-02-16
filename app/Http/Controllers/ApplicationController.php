<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function show(Request $request, $responseId = null)
    {
        return view('surveys.take', [
            'slug' => 'application1',
            'redirectUrl' => '/apply/thank-you',
        ]);
    }

    public function store(Request $request, $id = null)
    {
        // API handles save now via SurveyController
        return redirect('/apply/thank-you');
    }
}
