<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use App\Contracts\AssignableContract;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\AptitudeSubjectContract;
use App\Traits\AptitudeSubjectTrait;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\Relation;
use phpDocumentor\Reflection\Types\Boolean;

class CurationActivity extends Model implements AssignableContract, AptitudeSubjectContract
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use AptitudeSubjectTrait;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name',
        'legacy_name'
    ];

    
    public function assignments(): Relation
    {
        return $this->morphMany(Assignment::class, 'assignable');
    }

    public function expertPanels()
    {
        return $this->hasMany(ExpertPanel::class);
    }
    
    public function curationActivityType()
    {
        return $this->belongsTo(CurationActivityType::class);
    }


    public function scopeOfType($query, $typeId)
    {
        return $query->where('curation_activity_type_id', $typeId);
    }

    public function scopeExpertPanelType($query)
    {
        return $query->oftype(config('project.curation-activity-types.expert-panel'));
    }
    
    public function scopeComprehensive($query)
    {
        return $query->expertPanelType();
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

    public function canBeAssignedToBaseline():bool
    {
        if ($this->name == 'Baseline') {
            return true;
        }

        return false;
    }
}
