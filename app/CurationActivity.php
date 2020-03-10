<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use App\Traits\AssignableTrait;
use App\Traits\AptitudeSubjectTrait;
use App\Contracts\AssignableContract;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\AptitudeSubjectContract;
use phpDocumentor\Reflection\Types\Boolean;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CurationActivity extends Model implements AssignableContract, AptitudeSubjectContract
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use AptitudeSubjectTrait;
    use AssignableTrait;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name',
        'legacy_name'
    ];

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
