<?php

namespace App\Services\Reports;

use App\Application;
use App\Contracts\ReportGenerator;
use Illuminate\Support\Collection;
use App\Services\Search\VolunteerSearchService;

class ApplicationReportGenerator implements ReportGenerator
{
    protected $volunteerSearchService;
    protected $query;

    public function __construct(VolunteerSearchService $volunteerSearchService)
    {
        $this->volunteerSearchService = $volunteerSearchService;
        $this->query = Application::query()->finalized();
    }

    public function generate($filterParams = []):Collection
    {
        if ($filterParams) {
            $this->filterByVolunteer($filterParams);
        }

        $applications = $this->query->get();

        $questionDefs = class_survey()::findBySlug('application1')->getQuestions();
        return $applications->map(function ($application) use ($questionDefs) {
            foreach ($questionDefs as $definition) {
                $qName = $definition->getName();
                $response = $this->getReadableResponse($application->{$qName}, $definition);
                $application->{$qName} = $response ? $response : '';
            }
            return $application;
        });
    }

    public function addConstraint(callable $func)
    {
        $func($this->query);

        return $this;
    }

    private function getReadableResponse($responseValue, $questionDef)
    {
        $readableValue = $responseValue;
        if ($questionDef->hasOptions()) {
            $readableValue = array_map(
                function ($optionBlock) {
                    return $optionBlock->label;
                },
                $questionDef->getOptionsForResponseValue($responseValue)
            );
            if ($questionDef->numSelectable == 1) {
                $readableValue = isset($readableValue[0]) ? $readableValue[0] : '';
            }
        }

        return $readableValue;
    }

    private function filterByVolunteer($params)
    {
        $params = array_merge($params, ['select' => ['users.id']]);
        $volunteers = $this->volunteerSearchService->search($params);
        $this->query->whereIn('respondent_id', $volunteers->pluck('id'));
    }
}
