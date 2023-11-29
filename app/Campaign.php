<?php

namespace App;

use App\Traits\HasOtherOption;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Campaign extends Model
{
    use SoftDeletes;
    use RevisionableTrait;
    use HasOtherOption;
    use CrudTrait;

    protected $revisionCreationsEnabled = true;
    protected $fillable = [
        'name',
        'active',
        'starts_at',
        'ends_at',
        'display_order'
    ];
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('display_order', 'asc');
        });

        static::saved(function ($model) {
            Cache::forget('surveys:datasource:App\Surveys\SurveyOptions@adCampaigns');
        });
        static::deleted(function ($model) {
            Cache::forget('surveys:datasource:App\Surveys\SurveyOptions@adCampaigns');
        });        
    }
}
