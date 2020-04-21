<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    public function show(Request $request)
    {
        return view('application-thank-you');
    }
}
