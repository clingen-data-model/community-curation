<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait IsNotable
{
    public function notes():MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }
}
