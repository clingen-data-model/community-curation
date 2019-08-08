<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\CrudTrait;

class VolunteerStatus extends Model
{
    use RevisionableTrait;
    use SoftDeletes;
    use CrudTrait;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name'
    ];
}
