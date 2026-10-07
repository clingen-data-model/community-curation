<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class AssignmentStatus extends Model
{
    use RevisionableTrait;

    protected $fillable = [
        'names',
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
