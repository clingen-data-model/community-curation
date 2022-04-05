<?php

namespace App;

use App\Contracts\AssignableContract;
use App\Contracts\IsNotable;
use App\Traits\AssignableTrait;
use App\Traits\IsNotable as TraitsIsNotable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class CurationGroup extends Model implements AssignableContract, IsNotable
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use AssignableTrait;
    use TraitsIsNotable;
    use HasFactory;

    protected $fillable = [
        'name',
        'curation_activity_id',
        'working_group_id',
        'accepting_volunteers',
        'url',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($cg) {
            $cg->assignments->each->delete();
            CustomSurvey::where('curation_group_id', $cg->id)
                ->get()
                ->each->delete();
            Priority::where('curation_group_id', $cg->id)
                ->get()
                ->each->delete();
        });
    }

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
