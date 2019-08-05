<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class WorkingGroup extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    
    protected $fillable = [
        'name'
    ];

    public function expertPanels()
    {
        return $this->hasMany(ExpertPanel::class);
    }
    

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
