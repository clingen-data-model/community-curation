<?php

namespace App\Services\Search;

use Illuminate\Support\Collection;
use App\Contracts\ModelSearchService;
use App\ExpertPanel;
use Illuminate\Database\Eloquent\Builder;

class CurationGroupSearchService implements ModelSearchService
{
    protected $validFilters = [
        'curation_activity_id',
        'working_group_id',
        'accepting_volunteers'
    ];

    public function search($params):Collection
    {
        return $this->buildQuery($params)
            ->get();
    }

    public function buildQuery($params):Builder
    {
        $query = ExpertPanel::query()
                    ->select(['expert_panels.*']);
        foreach ($params as $key => $value) {
            if ($key == 'select') {
                $query->select($value);
            }

            if ($key == 'with') {
                $query->with($value);
            }
            if (in_array($key, $this->validFilters)) {
                $query->where($key, $value);
            }
        }

        if (isset($params['searchTerm'])) {
            $this->filterBySearchTerm($params['searchTerm'], $query);
        }

        $this->setOrder($params, $query);
 
        return $query;
    }

    private function filterBySearchTerm($searchTerm, $query)
    {
        $query->leftJoin('curation_activities', 'expert_panels.curation_activity_id', '=', 'curation_activities.id')
            ->leftJoin('working_groups', 'expert_panels.working_group_id', '=', 'working_groups.id')
            ->select('expert_panels.*');

        $query->where(function ($q) use ($searchTerm) {
            $q->where('expert_panels.name', 'like', '%'.$searchTerm.'%')
                ->orWhere('curation_activities.name', 'like', '%'.$searchTerm.'%')
                ->orWhere('working_groups.name', 'like', '%'.$searchTerm.'%');
        });
    }

    private function setOrder($params, $query)
    {
        $sortField = (isset($params['sortBy'])) ? $params['sortBy'] : 'expert_panels.name';
        $sortDir = (isset($params['sortDesc']) && $params['sortDesc'] === 'true') ? 'desc' : 'asc';
        if ($params['sortBy'] == 'curation_activity.name') {
            $query->addSelect('curation_activities.name as curation_activities.name');
            $query->leftJoin('curation_activities', 'expert_panels.curation_activity_id', '=', 'curation_activities.id');
            $query->orderBy('curation_activities.name', $sortDir);
            return;
        }
        if ($params['sortBy'] == 'working_group.name') {
            $query->addSelect('working_groups.name as working_groups.name');
            $query->leftJoin('working_groups', 'expert_panels.working_group_id', '=', 'working_groups.id');
            $query->orderBy('working_groups.name', $sortDir);
            return;
        }
        $query->orderBy($sortField, $sortDir);
    }
}
