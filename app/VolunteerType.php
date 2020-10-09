<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class VolunteerType extends Model
{
    use RevisionableTrait;
    use SoftDeletes;
    use CrudTrait;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name',
    ];
}
