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
        $this->info($this->option('report'));
        $this->info($this->option('start_date'));
        $this->info($this->option('end_date'));
    }
}
