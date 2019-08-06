<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Aptitude extends Model
{
    use RevisionableTrait;
    use SoftDeletes;


    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name',
        'volunteer_type_id',
        'curation_activity_id'
    ];

    public function volunteerType()
    {
        return $this->belongsTo(VolunteerType::class);
    }

    public function curationActivity()
    {
        return $this->belongsTo(CurationActivity::class);
    }
    
}
