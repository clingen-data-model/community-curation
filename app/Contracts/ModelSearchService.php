<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface ModelSearchService
{
    public function buildQuery($params): Builder;

    public function search($params): Collection;
}
