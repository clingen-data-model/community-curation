<?php

namespace App;

use App\Traits\HasOtherOption;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Goal extends Model
{
    use SoftDeletes;
    use RevisionableTrait;
    use HasOtherOption;
    use CrudTrait;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name',
        'active',
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            Cache::forget('surveys:datasource:App\Surveys\SurveyOptions@adCampaigns');
        });
        static::deleted(function ($model) {
            Cache::forget('surveys:datasource:App\Surveys\SurveyOptions@adCampaigns');
        });        
    }

}
