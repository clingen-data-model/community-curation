<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurationActivityType extends Model
{
    public $fillable = [
        'name',
        'description',
    ];

    public function curationActivities()
    {
        return $this->hasMany(CurationActivity::class);
    }
}
