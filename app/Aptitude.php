<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class Aptitude extends Model
{
    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name',
        'training_materials_url',
        'subject_type',
        'subject_id',
        'volunteer_type_id'
    ];

    public function subject()
    {
        return $this->morphTo();
    }
}
