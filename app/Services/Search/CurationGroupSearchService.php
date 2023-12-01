<?php

namespace App\Services\Search;

use App\CurationGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CurationGroupSearchService extends AbstractModelSearchService
{
    protected $validFilters = [
        'curation_activity_id',
        'working_group_id',
        'accepting_volunteers',
    ];

    public function search($params): Collection
    {
        return $this->buildQuery($params)
            ->get();
    }

    public function buildQuery($params): Builder
    {
        $query = CurationGroup::query()
                    ->select(['curation_groups.*']);
        // $query = CurationGroup::query()
        //             ->select(['curation_groups.*']);
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
        if (! $this->joinsTable($query, 'curation_activities')) {
            $query->leftJoin('curation_activities', 'curation_groups.curation_activity_id', '=', 'curation_activities.id');
        }
        if (! $this->joinsTable($query, 'working_groups')) {
            $query->leftJoin('working_groups', 'curation_groups.working_group_id', '=', 'working_groups.id');
        }
        $query->select('curation_groups.*');

        $query->where(function ($q) use ($searchTerm) {
            $q->where('curation_groups.name', 'like', '%'.$searchTerm.'%')
                ->orWhere('curation_activities.name', 'like', '%'.$searchTerm.'%')
                ->orWhere('working_groups.name', 'like', '%'.$searchTerm.'%');
        });
    }

    private function setOrder($params, $query)
    {
        $sortField = (isset($params['sortBy'])) ? $params['sortBy'] : 'curation_groups.name';
        $sortDir = (isset($params['sortDesc']) && $params['sortDesc'] === 'true') ? 'desc' : 'asc';
        if ($sortField == 'curation_activity.name') {
            $query->addSelect('curation_activities.name as curation_activities.name');
            if (! $this->joinsTable($query, 'curation_activities')) {
                $query->leftJoin('curation_activities', 'curation_groups.curation_activity_id', '=', 'curation_activities.id');
            }
            $query->orderBy('curation_activities.name', $sortDir);

            return;
        }
        if ($sortField == 'working_group.name') {
            $query->addSelect('working_groups.name as working_groups.name');
            if (! $this->joinsTable($query, 'working_groups')) {
                $query->leftJoin('working_groups', 'curation_groups.working_group_id', '=', 'working_groups.id');
            }
            $query->orderBy('working_groups.name', $sortDir);

            return;
        }
        $query->orderBy($sortField, $sortDir);
    }
}
