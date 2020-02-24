<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\Relation;

interface AssignableContract
{
    public function assignments(): Relation;
    public function canBeAssignedToBaseline(): bool;
}
