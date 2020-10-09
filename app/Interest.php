<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Interest extends Model
{
    use SoftDeletes;
    use RevisionableTrait;
    use CrudTrait;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name',
        'active',
    ];
}
