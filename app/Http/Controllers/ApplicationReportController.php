<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\Reports\ApplicationReportWriter;
use App\Services\Reports\ApplicationReportGenerator;

class ApplicationReportController extends Controller
{
    protected $generator;

    protected $writer;

    public function __construct(ApplicationReportGenerator $generator, ApplicationReportWriter $writer)
    {
        $this->generator = $generator;
        $this->writer = $writer;
    }

    public function index(Request $request)
    {
        $filePath = storage_path('app/reports/application-report-'.Carbon::now()->format('Y-m-d_H:i:s').'.xlsx');

        $this->writer
            ->setPath($filePath)
            ->writeData($this->generator->generate());

        
        return response()
            ->download($filePath)
            ->deleteFileAfterSend();
    }
}
