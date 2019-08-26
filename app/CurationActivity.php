<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use App\Contracts\AssignableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class CurationActivity extends Model implements AssignableContract
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;

    protected $revisionCreationsEnabled = true;    

    protected $fillable = [
        'name'
    ];

    public function assignments(): Relation
    {
        return $this->morphMany(Assignment::class, 'assignable');
    }
    
}
