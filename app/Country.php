<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Country extends Model
{
    use CrudTrait;

    protected $fillable = [
        'name',
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

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function allAsOptions()
    {
        return static::orderBy('name')->get();
    }

    public static function query()
    {
        return parent::query()->orderBy('name');
    }
}
