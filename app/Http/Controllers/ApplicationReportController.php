<?php

namespace App\Http\Controllers;

use App\Services\Reports\ApplicationReportGenerator;
use App\Services\Reports\ApplicationReportWriter;
use ApplicationReportBuild;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
