<?php

namespace App;

use App\Traits\HasOtherOption;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Venturecraft\Revisionable\RevisionableTrait;

class SelfDescription extends Model
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
