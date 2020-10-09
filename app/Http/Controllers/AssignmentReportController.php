<?php

namespace App\Http\Controllers;

use App\Services\Reports\AssignmentReportGenerator;
use App\Services\Reports\AssignmentReportWriter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AssignmentReportController extends Controller
{
    private $writer;
    private $generator;

    public function __construct(AssignmentReportWriter $writer, AssignmentReportGenerator $generator)
    {
        $this->writer = $writer;
        $this->generator = $generator;
    }

    public function index(Request $request)
    {
        $filePath = storage_path('app/reports/assignments-report-'.Carbon::now()->format('Y-m-d_H:i:s').'.xlsx');

        $this->writer
            ->setPath($filePath)
            ->writeData($this->generator->generate($request->all()));

        return response()
            ->download($filePath)
            ->deleteFileAfterSend();
    }
}
