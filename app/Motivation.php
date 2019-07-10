<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Motivation extends Model
{
    use SoftDeletes;
    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;
    
    protected $fillable = [
        'name'
    ];
}
