<?php

namespace App\Http\Controllers;

use App\Actions\Reports\ApplicationReportBuild;
use Illuminate\Http\Request;

class ApplicationReportController extends Controller
{
    public function __construct(private ApplicationReportBuild $buildReport)
    {
    }

    public function index(Request $request)
    {
        return response()
            ->download($this->buildReport->handle($request->all()))
            ->deleteFileAfterSend();
    }
}
