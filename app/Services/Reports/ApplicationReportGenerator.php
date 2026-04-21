<?php

namespace App\Services\Reports;

use App\Actions\ApplicationReportRowCreate;
use App\Application;
use App\ApplicationReportRow;
use App\Contracts\ReportGenerator;
use App\Country;
use App\Services\Search\VolunteerSearchService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class ApplicationReportGenerator implements ReportGenerator
{
    protected $applicationQuestions;
    protected $volunteerSearchService;
    protected array $constraints = [];

    public function __construct(VolunteerSearchService $volunteerSearchService)
    {
        $this->volunteerSearchService = $volunteerSearchService;
        $this->applicationQuestions = class_survey()::findBySlug('application1')->getQuestions();
    }

    public function generate($filterParams = []): Collection
    {
        return $this->query($filterParams)
            ->select('data')
            ->get()
            ->pluck('data');
    }

    public function addConstraint(callable $func)
    {
        $this->constraints[] = $func;

        return $this;
    }

    public function firstRow(array $filterParams = []): ?array
    {
        $row = $this->query($filterParams)
            ->select(['id', 'data'])
            ->orderBy('id')
            ->first();

        return $row?->data;
    }

    public function streamRows(array $filterParams = [], int $chunkSize = 250): LazyCollection
    {
        return $this->query($filterParams)
            ->select(['id', 'data'])
            ->orderBy('id')
            ->lazyById($chunkSize, 'id')
            ->map(function (ApplicationReportRow $row) {
                return $row->data;
            });
    }

    protected function query(array $filterParams = []): Builder
    {
        $query = ApplicationReportRow::query();

        foreach ($this->constraints as $constraint) {
            $constraint($query);
        }

        if ($filterParams) {
            $this->filterByVolunteer($query, $filterParams);
        }

        return $query;
    }

    private function filterByVolunteer(Builder $reportQuery, array $params): void
    {
        $volunteerQuery = $this->volunteerSearchService
            ->buildQuery(array_merge($params, ['for_report' => true]))
            ->setEagerLoads([])
            ->select('users.id');

        $reportQuery->whereIn('user_id', $volunteerQuery->toBase());
    }
}
