<?php

namespace App\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

interface ModelSearchService
{
    public function buildQuery($params):Builder;
    public function search($params):Collection;
}
