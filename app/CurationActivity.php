<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurationActivity extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];
}
