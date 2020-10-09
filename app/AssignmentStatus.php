<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class AssignmentStatus extends Model
{
    use RevisionableTrait;
    use CrudTrait;

    protected $fillable = [
        'names',
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
