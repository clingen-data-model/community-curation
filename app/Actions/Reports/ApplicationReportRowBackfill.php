<?php
namespace App\Actions;

use App\Application;
use App\Actions\ApplicationReportRowCreate;

class ApplicationReportRowBackfill
{
  public function __construct(private ApplicationReportRowCreate $creatAction)
  {}

  public function handle()
  {
    Application::query()
      ->hasRespondent()
      ->with('user', 'country', 'user.priorities', 'user.priorities.curationActivity', 'user.priorities.curationGroup')
      ->finalized()
      ->get()
      ->each(function ($application) {
      $this->creatAction->handle($application);
    });
  }
}
