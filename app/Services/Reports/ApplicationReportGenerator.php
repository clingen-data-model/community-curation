<?php

namespace App\Services\Reports;

use App\Actions\ApplicationReportRowCreate;
use App\Application;
use App\ApplicationReportRow;
use App\Contracts\ReportGenerator;
use App\Country;
use App\Services\Search\VolunteerSearchService;
use Illuminate\Support\Collection;

class ApplicationReportGenerator implements ReportGenerator
{
    protected $applicationQuestions;
    protected $volunteerSearchService;
    protected $query;

    public function __construct(VolunteerSearchService $volunteerSearchService)
    {
        $this->volunteerSearchService = $volunteerSearchService;
        $this->query = ApplicationReportRow::query();
        $this->applicationQuestions = class_survey()::findBySlug('application1')->getQuestions();
    }

    public function generate($filterParams = []): Collection
    {
        if ($filterParams) {
            $this->filterByVolunteer($filterParams);
        }

        $data = $this->query->select('data')->get()->pluck('data');

        return $data;

    }

    public function addConstraint(callable $func)
    {
        $func($this->query);

        return $this;
    }

    private function filterByVolunteer($params)
    {
        $params = array_merge($params, ['select' => ['users.id']]);
        $volunteers = $this->volunteerSearchService->search($params);
        $this->query->whereIn('user_id', $volunteers->pluck('id'));
    }
}
