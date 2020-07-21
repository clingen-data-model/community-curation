<?php

namespace App;

use App\User;
use Backpack\CRUD\CrudTrait;
use App\Traits\AssignableTrait;
use App\Contracts\AssignableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\Relation;

class CurationGroup extends Model implements AssignableContract
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use AssignableTrait;

    protected $fillable = [
        'name',
        'curation_activity_id',
        'working_group_id',
        'accepting_volunteers',
        'url'
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

    public function scopeAcceptingVolunteers($query)
    {
        return $query->where('accepting_volunteers', 1);
    }

    public function getParentAssignable(): AssignableContract
    {
        return $this->curationActivity;
    }
}
