<?php

namespace App\Services\Search;

use Illuminate\Database\Eloquent\Builder;

class VolunteerSearchService extends UserSearchService
{
    protected static $validFilters = [
        'first_name',
        'last_name',
        'name',
        'volunteer_status_id',
        'volunteer_type_id',
    ];

    public function __construct()
    {
        self::$validFilters = array_merge(self::$validFilters, parent::$validFilters);
    }

    public function buildQuery($params): Builder
    {
        $with = [
            'volunteerType',
            'volunteerStatus',
            'structuredAssignments',
        ];

        $query = parent::buildQuery($params)
            ->isVolunteer();

        foreach ($params as $key => $value) {
            if ($key == 'without') {
                $with = array_diff($with, $value);
            }

            if ($key == 'curation_group_id') {
                $this->filterByCurationGroup($value, $query);
            }

            if ($key == 'curation_activity_id') {
                $this->filterByCurationActivity($value, $query);
            }

            if ($key == 'gene_id') {
                $this->filterByGene($value, $query);
            }

            if ($key == 'highest_ed') {
                $query->with(['application' => function ($q) {
                    $q->select('respondent_type', 'respondent_id', 'survey_id', 'highest_ed');
                }]);
            }
        }

        $query->with($with);

        return $query;
    }

    private function filterByCurationGroup($value, $query)
    {
        if ($value == -1) {
            $query->comprehensive()->whereDoesntHave('assignments', function ($q) {
                $q->curationGroup();
            });
        } else {
            $query->whereHas('assignments', function ($q) use ($value) {
                $q->where([
                    'assignable_type' => 'App\CurationGroup',
                    'assignable_id' => $value,
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
                    'assignable_id' => $value,
                ]);
            });
        }
    }

    private function filterByGene($value, $query)
    {
        $query->whereHas('assignments', function ($q) use ($value) {
            $q->where([
                'assignable_type' => 'App\Gene',
                'assignable_id' => $value,
            ]);
        });
    }

    protected function filterBySearchTerm($searchTerm, $query)
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
}
