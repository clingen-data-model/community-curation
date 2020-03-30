<?php

namespace App\Traits;

use App\Assignment;
use App\CurationActivity;
use App\Contracts\AssignableContract;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Default assignable methods to implment AssignableContract
 */
trait AssignableTrait
{
    public function assignments(): Relation
    {
        return $this->morphMany(Assignment::class, 'assignable');
    }
    
    public function canBeAssignedToBaseline(): bool
    {
        return false;
    }

    public function getParentAssignable(): AssignableContract
    {
        return new CurationActivity;
    }
}
