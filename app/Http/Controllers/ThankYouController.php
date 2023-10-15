<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    public function show(Request $request): View
    {
        return view('application-thank-you');
    }
}
