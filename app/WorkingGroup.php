<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class WorkingGroup extends Model
{
    use CrudTrait;
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
