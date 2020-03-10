<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use App\Contracts\AssignableContract;
use App\Traits\AssignableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\Relation;

class ExpertPanel extends Model implements AssignableContract
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use AssignableTrait;

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

    public function scopeAcceptingVolunteers($query)
    {
        return $query->where('accepting_volunteers', 1);
    }
}
