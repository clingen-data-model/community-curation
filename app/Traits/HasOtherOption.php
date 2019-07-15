<?php

namespace App\Traits;

/**
 * trait for models that are used as options and have 'other' option
 */
trait HasOtherOption
{
    public function scopeWithoutOther($query)
    {
        return $query->where('name', '!=', 'Other');
    }
}
