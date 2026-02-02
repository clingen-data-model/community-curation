<?php

namespace App\Actions;

use App\Volunteer6MonthSurvey;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class SixMonthFollowupExport
{
    use AsController;

    public function __construct(private SurveyDataExport $dataExport)
    {
    }

    public function handle()
    {
        $filepath = $this->dataExport->handle(
            Volunteer6MonthSurvey::class,
            null,
            'volunteer-six-month1'
        );

        $pathparts = explode('/', $filepath);
        $filename = array_pop($pathparts);

        return response()->download($filepath, $filename);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('run reports');
    }
}
