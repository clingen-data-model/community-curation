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
       
        if ($request->start_date) {
            $this->generator
                ->addConstraint(function ($q) use ($request) {
                    $q->where('finalized_at', '>=', $request->start_date);
                });
        }

        if ($request->end_date) {
            $this->generator
                ->addConstraint(function ($q) use ($request) {
                    $q->where('finalized_at', '<=', $request->end_date);
                });
        }

        $data = $this->generator->generate($request->all());

        $this->writer
            ->setPath($filePath)
            ->writeData($data);

        
        return response()
            ->download($filePath)
            ->deleteFileAfterSend();
    }
}
