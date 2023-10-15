<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ThankYouController extends Controller
{
    public function show(Request $request): View
    {
        return view('application-thank-you');
    }
}
