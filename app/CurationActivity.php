<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use App\Contracts\AssignableContract;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\TrainingSubjectContract;
use App\Traits\TrainingSubjectTrait;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\Relation;

class CurationActivity extends Model implements AssignableContract, TrainingSubjectContract
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use TrainingSubjectTrait;

    protected $revisionCreationsEnabled = true;    

    protected $fillable = [
        'name'
    ];

    public function assignments(): Relation
    {
        return $this->morphMany(Assignment::class, 'assignable');
    }    
}
