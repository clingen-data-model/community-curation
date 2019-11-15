<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'name',
        'materials_url',
        'subject_type',
        'subject_id'
    ];

    public function subject()
    {
        return $this->morphTo();
    }
}
