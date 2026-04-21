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
        $filePath = $this->buildReport->handle($request->all());

        if (! $filePath) {
            return redirect()->back()->with('warning', 'Nothing matched your filters.');
        }

        return response()
            ->download($filePath)
            ->deleteFileAfterSend();
    }
}
