<?php

namespace App\Services\Search;

use App\User;
use Illuminate\Support\Collection;
use App\Contracts\ModelSearchService;
use Illuminate\Database\Eloquent\Builder;

class VolunteerSearchService implements ModelSearchService
{
    protected $validFilters = [
        'first_name',
        'last_name',
        'name',
        'volunteer_status_id',
        'volunteer_type_id',
    ];

    public function search($params):Collection
    {
        return $this->buildQuery($params)
                ->get();
    }

    public function buildQuery($params):Builder
    {
        $query = User::query()
                        ->with([
                            'volunteerType',
                            'volunteerStatus',
                            'structuredAssignments',
                        ])
                        ->isVolunteer();

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

            if ($key == 'expert_panel_id') {
                $this->filterByExpertPanel($value, $query);
            }

            if ($key == 'curation_activity_id') {
                $this->filterByCurationActivity($value, $query);
            }
 
            if ($key == 'gene_id') {
                $this->filterByGene($value, $query);
            }
        }

        if (isset($params['searchTerm'])) {
            $this->filterBySearchTerm($params['searchTerm'], $query);
        }

        $this->setOrder($params, $query);
 
        return $query;
    }

    private function filterByExpertPanel($value, $query)
    {
        if ($value == -1) {
            $query->comprehensive()->whereDoesntHave('assignments', function ($q) {
                $q->expertPanel();
            });
        } else {
            $query->whereHas('assignments', function ($q) use ($value) {
                $q->where([
                    'assignable_type' => 'App\ExpertPanel',
                    'assignable_id' => $value
                ]);
            });
        }
    }

    private function filterByCurationActivity($value, $query)
    {
        if ($value == -1) {
            $query->doesntHave('assignments');
        } else {
            $query->whereHas('assignments', function ($q) use ($value) {
                $q->where([
                    'assignable_type' => 'App\CurationActivity',
                    'assignable_id' => $value
                ]);
            });
        }
    }

    private function filterByGene($value, $query)
    {
        $query->whereHas('assignments', function ($q) use ($value) {
            $q->where([
                'assignable_type' => 'App\Gene',
                'assignable_id' => $value
            ]);
        });
    }

    private function filterBySearchTerm($searchTerm, $query)
    {
        $query->leftJoin('volunteer_statuses', 'users.volunteer_status_id', '=', 'volunteer_statuses.id')
            ->leftJoin('volunteer_types', 'users.volunteer_type_id', '=', 'volunteer_types.id')
            ->select('users.*');

        $query->where(function ($q) use ($searchTerm) {
            $q->where('first_name', 'like', '%'.$searchTerm.'%')
            ->orWhere('last_name', 'like', '%'.$searchTerm.'%')
            ->orWhere('email', 'like', '%'.$searchTerm.'%')
            ->orWhere('volunteer_statuses.name', 'like', '%'.$searchTerm.'%')
            ->orWhere('volunteer_types.name', 'like', '%'.$searchTerm.'%');
        });
    }

    private function setOrder($params, $query)
    {
        $sortField = (isset($params['sortBy'])) ? $params['sortBy'] : 'last_name';
        $sortDir = (isset($params['sortDesc']) && $params['sortDesc'] === 'true') ? 'desc' : 'asc';
        $query->orderBy($sortField, $sortDir);
    }
}