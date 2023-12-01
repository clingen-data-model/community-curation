<?php

namespace App\Services\Search;

use App\Contracts\ModelSearchService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

abstract class AbstractModelSearchService implements ModelSearchService
{
    abstract public function buildQuery($params): Builder;

    abstract public function search($params): Collection;

    protected function joinsTable($query, $table)
    {
        if (is_null($query->getQuery()->joins)) {
            return false;
        }
        foreach ($query->getQuery()->joins as $joinClause) {
            if ($joinClause->table == $table) {
                return true;
            }
        }

        return false;
    }
}
