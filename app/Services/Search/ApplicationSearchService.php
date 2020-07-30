<?php

namespace App\Services\Search;

use App\Application;
use Illuminate\Support\Collection;
use App\Contracts\ModelSearchService;
use Illuminate\Database\Eloquent\Builder;

class ApplicationSearchService implements ModelSearchService
{
    protected static $validFilters = [
        'created_at',
        'updatd_at',
        'respondent_id',
        'first_name',
        'last_name',
        'email',
        'volunteer_type',
    ];

    public function search($params) : Collection
    {
        return $this->buildQuery($params)->get();
    }

    public function buildQuery($params) : Builder
    {
        $query = Application::query();
        
        foreach ($params as $key => $value) {
            if ($key == 'select') {
                $query->select($value);
            }

            if ($key == 'with') {
                $query->with($value);
            }
            if (in_array($key, self::$validFilters)) {
                $query->where($key, $value);
            }
        }

        if (isset($params['finalized_at'])) {
            $this->filterByFinalizedAt($params['finalized_at'], $query);
        }
        $this->setOrder($params, $query);
 
        return $query;
    }

    private function filterByFinalizedAt($value, $query)
    {
        if ($value == 0) {
            $query->whereNull('finalized_at');
            return;
        }

        if ($value == 1) {
            $query->whereNotNull('finalized_at');
            return;
        }
        $query->whereNotNull('finalized_at')
            ->where('finalized_at', '>', $value);
    }
    
    protected function setOrder($params, $query)
    {
        $sortField = (isset($params['sortBy'])) ? $params['sortBy'] : 'last_name';
        $sortDir = (isset($params['sortDesc']) && $params['sortDesc'] === 'true') ? 'desc' : 'asc';
        $query->orderBy($sortField, $sortDir);
    }
}
