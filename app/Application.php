<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'rsp_application_1';
    protected $fillable = [];

    protected $casts = [
        'interests' => 'array',
        'goals' => 'array',
        'motivation' => 'array',
        'ad_campaign' => 'array'
    ];

    public function respondent()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->respondent();
    }
}
