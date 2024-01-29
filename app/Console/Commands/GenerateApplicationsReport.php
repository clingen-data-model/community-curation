<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Reports\ApplicationReportGenerator;
use App\Services\Reports\ApplicationReportWriter;

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
        $this->info('Generating report...');
        $reportPath = $this->option('report') ?? 'application-report-'.now()->format('Y-m-d_His').'.xlsx';
        $this->info($reportPath);
        $this->info($this->option('start_date'));
        $this->info($this->option('end_date'));

        // TODO add start/end constraints to generator

        $data = $this->generator->generate();

        $this->writer
            ->setPath($reportPath)
            ->writeData($data);
    }
}
