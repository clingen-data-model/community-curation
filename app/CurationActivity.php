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

class CurationActivity extends Model implements AssignableContract, AptitudeSubjectContract
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use AptitudeSubjectTrait;

    protected $revisionCreationsEnabled = true;    

    protected $fillable = [
        'name'
    ];

    public function assignments(): Relation
    {
        return $this->morphMany(Assignment::class, 'assignable');
    }

    public function expertPanels()
    {
        return $this->hasMany(ExpertPanel::class);
    }
    
}
