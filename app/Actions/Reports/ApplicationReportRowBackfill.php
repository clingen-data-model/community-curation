<?php
namespace App\Actions\Reports;

use App\Application;
use Lorisleiva\Actions\Concerns\AsCommand;
use App\Actions\Reports\ApplicationReportRowCreate;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class ApplicationReportRowBackfill
{
  use AsCommand;

  public string $commandSignature = 'application-report-row:backfill';
  private $rows;

  public function __construct(private ApplicationReportRowCreate $creatAction)
  {}

  public function handle()
  {
      $this->getRows()->each(function ($application) {
        $this->creatAction->handle($application);
      });
  }

  public function asCommand(Command $command): void
  {
    $rowCount = $this->getRows()->count();
    $start = microtime(true);
    $command->info("Found $rowCount applications to backfill.");
    $this->handle();
    $end = microtime(true);
    $command->info('Application report rows backfilled.');
  }

  private function getRows():Collection
  {
    if (!$this->rows) {
      $this->rows = Application::query()
        ->hasRespondent()
        ->with('user', 'country', 'user.priorities', 'user.priorities.curationActivity', 'user.priorities.curationGroup')
        ->finalized()
        ->get();
    }

    return $this->rows;
  }
}
