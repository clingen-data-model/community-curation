<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpertPanel extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'curation_activity_id',
        'working_group_id',
        'accepting_volunteers'
    ];

    public function workingGroup()
    {
        return $this->belongsTo(WorkingGroup::class);
    }
    

    public function curationActivity()
    {
        return $this->belongsTo(CurationActivity::class);
    }

    public function scopeForCurationActivity($query, $param)
    {
        return $query->where('curation_activity_id', $param);
    }
}
