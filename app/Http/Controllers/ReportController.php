<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('reports.index');
    }
}
