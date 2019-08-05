<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class ExpertPanel extends Model
{
    use CrudTrait;
    use RevisionableTrait;

    protected $fillable = [
        'name',
        'curation_activity_id',
        'working_group_id'
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
