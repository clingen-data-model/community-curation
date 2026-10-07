<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class WorkingGroup extends Model
{
    use RevisionableTrait;

    protected $fillable = [
        'name',
        'url',
    ];

    public function curationGroups()
    {
        return $this->hasMany(CurationGroup::class);
    }
}
