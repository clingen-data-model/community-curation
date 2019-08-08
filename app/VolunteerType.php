<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Backpack\CRUD\CrudTrait;

class VolunteerType extends Model
{
    use RevisionableTrait;
    use SoftDeletes;
    use CrudTrait;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name'
    ];
}
