<?php

namespace App\Services\Search;

use App\Contracts\ModelSearchService;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class UserSearchService implements ModelSearchService
{
    protected static $validFilters = [
        'first_name',
        'last_name',
        'name',
        'volunteer_status_id',
        'volunteer_type_id',
    ];

    public function search($params): Collection
    {
        return $this->buildQuery($params)
                ->get();
    }

    public function buildQuery($params): Builder
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

        if (isset($params['last_logged_in_at'])) {
            $this->filterByLastLoggedIn($params['last_logged_in_at'], $query);
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
        if (isset($params['sortBy']) && $params['sortBy'] == '') {
            return;
        }
        $sortField = (isset($params['sortBy'])) ? $params['sortBy'] : 'last_name';
        $sortDir = (isset($params['sortDesc']) && $params['sortDesc'] === 'true') ? 'desc' : 'asc';
        $query->orderBy($sortField, $sortDir);
    }

    protected function filterByLastLoggedIn($value, $query)
    {
        if ($value == 0) {
            $query->whereNull('last_logged_in_at');

            return;
        }

        if ($value == 1) {
            $query->whereNotNull('last_logged_in_at');

            return;
        }
        $query->whereNotNull('last_logged_in_at')
            ->where('last_logged_in_at', '>', $value);
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
