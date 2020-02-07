<?php

namespace App;

use App\Campaign;
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

    public function survey()
    {
        return $this->belongsTo(class_survey());
    }
    

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function volunteerType()
    {
        return $this->belongsTo(VolunteerType::class, 'volunteer_type');
    }

    public function selfDescription()
    {
        return $this->belongsTo(SelfDescription::class, 'self_desc');
    }


    public function getSurveyDocAttribute()
    {
        return $this->survey->document;
    }

    public function getHighestEdAttribute()
    {
        return $this->getValueFromOptions('highest_ed');
    }

    public function getTimezoneAttribute()
    {
        return $this->getValueFromOptions('timezone');
    }

    public function getAdCampaignAttribute()
    {
        return Campaign::find(json_decode($this->attributes['ad_campaign']))->pluck('name');
    }

    public function getMotivationAttribute()
    {
        return Motivation::find(json_decode($this->attributes['motivation']))->pluck('name');
    }

    public function getGoalsAttribute()
    {
        return Goal::find(json_decode($this->attributes['goals']))->pluck('name');
    }

    public function getInterestsAttribute()
    {
        return Interest::find(json_decode($this->attributes['interests']))->pluck('name');
    }

    private function getValueFromOptions($attribute)
    {
        return ($this->survey->document->questions[$attribute]->getOptionByValue($this->attributes[$attribute])) 
            ? $this->survey->document->questions[$attribute]->getOptionByValue($this->attributes[$attribute])->label
            : null;
    }
    
}
