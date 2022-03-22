<?php

namespace App\Actions;

use App\Volunteer3MonthSurvey;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class ThreeMonthFollowupExport
{
    use AsController;
    
    public function __construct(private SurveyDataExport $dataExport)
    {
    }

    public function handle()
    {
        $filepath = $this->dataExport->handle(Volunteer3MonthSurvey::class);

        $pathparts = explode('/', $filepath);
        $filename = array_pop($pathparts);

        return response()->download($filepath, $filename);
    }
    
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('run reports');
    }
}
