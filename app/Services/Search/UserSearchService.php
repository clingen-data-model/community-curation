<?php

namespace App\Services\Search;

use App\User;
use Illuminate\Support\Collection;
use App\Contracts\ModelSearchService;
use Illuminate\Database\Eloquent\Builder;

class UserSearchService implements ModelSearchService
{
    protected static $validFilters = [
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
        $query = User::query();

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

        if (isset($params['searchTerm'])) {
            $this->filterBySearchTerm($params['searchTerm'], $query);
        }

        if (isset($params['is_logged_in'])) {
            $query->isLoggedIn();
        }

        $this->setOrder($params, $query);
 
        return $query;
    }


    protected function setOrder($params, $query)
    {
        $sortField = (isset($params['sortBy'])) ? $params['sortBy'] : 'last_name';
        $sortDir = (isset($params['sortDesc']) && $params['sortDesc'] === 'true') ? 'desc' : 'asc';
        $query->orderBy($sortField, $sortDir);
    }

    protected function filterBySearchTerm($searchTerm, $query)
    {
        $query->where(function ($q) use ($searchTerm) {
            $q->where('first_name', 'like', '%'.$searchTerm.'%')
            ->orWhere('last_name', 'like', '%'.$searchTerm.'%')
            ->orWhere('email', 'like', '%'.$searchTerm.'%');
        });
    }
}
