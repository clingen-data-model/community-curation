<?php

namespace App\DbMailLog\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Email
{
    public function scopeFrom($query, $from): Builder;

    public function scopeLikeFrom($query, $from): Builder;

    public function scopeTo($query, $to): Builder;

    public function scopeLikeTo($query, $to): Builder;
}
