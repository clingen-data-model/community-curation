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
        'volunteer_type_id',
    ];

    public function volunteerType()
    {
        return $this->belongsTo(VolunteerType::class);
    }
}
