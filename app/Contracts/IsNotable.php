<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface IsNotable
{
    public function notes(): MorphMany;
}
