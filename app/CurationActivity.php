<?php

namespace App;

use App\Contracts\AptitudeSubjectContract;
use App\Contracts\AssignableContract;
use App\Contracts\TrainingTopicContract;
use App\Traits\AptitudeSubjectTrait;
use App\Traits\AssignableTrait;
use App\Traits\TrainingTopicTrait;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class CurationActivity extends Model implements AssignableContract, AptitudeSubjectContract, TrainingTopicContract
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use AptitudeSubjectTrait;
    use AssignableTrait;
    use TrainingTopicTrait;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name',
        'legacy_name',
    ];

    public function curationGroups()
    {
        return $this->hasMany(CurationGroup::class);
    }

    public function curationActivityType()
    {
        return $this->belongsTo(CurationActivityType::class);
    }

    public function scopeOfType($query, $typeId)
    {
        return $query->where('curation_activity_type_id', $typeId);
    }

    public function scopeCurationGroupType($query)
    {
        return $query->oftype(config('project.curation-activity-types.curation-group'));
    }

    public function scopeComprehensive($query)
    {
        return $query->curationGroupType();
    }

    public function scopeGeneType($query)
    {
        return $query->ofType(config('project.curation-activity-types.gene'));
    }

    public function scopeBaseline($query)
    {
        return $query->geneType();
    }

    public static function findByName($nameString)
    {
        return static::query()->where('name', $nameString)->first();
    }

    public function canBeAssignedToBaseline(): bool
    {
        if ($this->name == 'Baseline') {
            return true;
        }

        return false;
    }
}
