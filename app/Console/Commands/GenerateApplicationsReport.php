<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Reports\ApplicationReportGenerator;
use App\Services\Reports\ApplicationReportWriter;
use Illuminate\Support\Facades\Log;

class GenerateApplicationsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applications:report {--start_date=} {--end_date=} {--report=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate report of all curator applications';

    protected $generator;
    protected $writer;

    public function __construct(ApplicationReportGenerator $generator, ApplicationReportWriter $writer)
    {
        $this->generator = $generator;
        $this->writer = $writer;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('TIMEPOINT starting report generation');
        $this->info('Generating report...');
        $reportPath = $this->option('report') ?? 'application-report-'.now()->format('Y-m-d_His').'.xlsx';
        $this->info($reportPath);
        $this->info($this->option('start_date'));
        $this->info($this->option('end_date'));

        if ($this->option('start_date')) {
            $this->generator
                ->addConstraint(function ($q) {
                    $q->where('finalized_at', '>=', $this->option('start_date'));
                });
        }

        if ($this->option('end_date')) {
            $this->generator
                ->addConstraint(function ($q) {
                    $q->where('finalized_at', '<=', $this->option('end_date'));
                });
        }

        $data = $this->generator->generate();

        Log::info('TIMEPOINT starting write');
        $this->writer
            ->setPath($reportPath)
            ->writeData($data);
        Log::info('TIMEPOINT finishing write');
    }
}
