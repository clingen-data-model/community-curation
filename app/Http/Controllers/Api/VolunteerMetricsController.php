<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Search\ApplicationSearchService;
use App\Services\Search\VolunteerSearchService;
use App\VolunteerStatus;
use App\VolunteerType;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class VolunteerMetricsController extends Controller
{
    protected $volunteerSearch;

    protected $applicationSearch;

    public function __construct(VolunteerSearchService $volunteerSearch, ApplicationSearchService $applicationSearch)
    {
        $this->volunteerSearch = $volunteerSearch;
        $this->applicationSearch = $applicationSearch;
    }

    public function index(Request $request)
    {
        $params = $request->all();

        $query = $this->volunteerSearchQuery($params, 'volunteer_status_id')
            ->union($this->volunteerSearchQuery($params, 'volunteer_type_id'))
            ->union($this->applicationSearchQuery($params, 'highest_ed'))
            ->union($this->applicationSearchQuery($params, 'self_desc'))
            ->union($this->applicationSearchQuery($params, 'race_ethnicity'));

        $counts = $query->get()
            ->groupBy('label')
            ->map(function ($group) {
                return $group->pluck('count', 'key');
            });

        $counts = $this->formatData($counts);

        return response($counts->toJson(), 200, ['Content-Type' => 'text/json']);
    }

    private function formatData($counts): Collection
    {
        $surveyDef = class_survey()::findBySlug('application1')->document;
        $volunteerStatuses = VolunteerStatus::all()->keyBy('id');
        $volunteerTypes = VolunteerType::all()->keyBy('id');

        $paramMap = [
            [
                'attr' => 'highest_ed',
                'label' => 'education',
                'keyBy' => function ($count, $key) use ($surveyDef) {
                    return empty($key) ? 'Unknown' : $surveyDef->questions['highest_ed']->getOptionByValue($key)->label;
                },
            ],
            [
                'attr' => 'self_desc',
                'label' => 'self_description',
                'keyBy' => function ($count, $key) use ($surveyDef) {
                    return empty($key) ? 'Unknown' : $surveyDef->questions['self_desc']->getOptionByValue($key)->label;
                },
            ],
            [
                'label' => 'race/ethnicity',
                'attr' => 'race_ethnicity',
                'keyBy' => function ($count, $key) {
                    return empty($key) ? 'unknown' : json_decode($key)[0];
                },
            ],
            [
                'label' => 'volunteer_type_id',
                'attr' => 'volunteer_type_id',
                'keyBy' => function ($count, $key) use ($volunteerTypes) {
                    return empty($key) ? 'Unknown' : $volunteerTypes->get($key)->name;
                },
            ],
            [
                'label' => 'volunteer_status_id',
                'attr' => 'volunteer_status_id',
                'keyBy' => function ($count, $key) use ($volunteerStatuses) {
                    return empty($key) ? 'Unknown' : $volunteerStatuses->get($key)->name;
                },
            ],
        ];

        foreach ($paramMap as $map) {
            if ($counts->get($map['attr'])) {
                $counts->put($map['label'], $counts->get($map['attr'])->keyBy($map['keyBy']));
            }
        }

        return $counts->except(['highest_ed', 'self_desc', 'race_ethnicity']);
    }

    private function volunteerSearchQuery($params, $column, $label = null)
    {
        $params['sortBy'] = '';
        $query = $this->volunteerSearch->buildQuery($params);
        $query->groupBy($column)
            ->without([
                'volunteerType',
                'volunteerStatus',
                'structuredAssignments',
            ])
            ->select([])
            ->selectRaw('"'.(($label) ? $label : $column).'" as `label`')
            ->selectRaw($column.' as `key`')
            ->selectRaw('count(*) as `count`');

        if (isset($params['start_date']) && $params['start_date']) {
            $query->where('users.created_at', '>=', $params['start_date']);
        }

        if (isset($params['end_date']) && $params['end_date']) {
            $query->where('users.created_at', '<=', $params['end_date']);
        }

        return $query;
    }

    private function applicationSearchQuery($params, $column, $label = null)
    {
        $params['sortBy'] = '';
        $query = $this->volunteerSearchQuery($params, 'rsp_application_1.'.$column, $label ?? $column);
        $query->leftJoin('rsp_application_1', 'users.id', '=', 'rsp_application_1.respondent_id');

        return $query;
    }
}
