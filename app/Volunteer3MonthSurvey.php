<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Volunteer3MonthSurvey extends Model
{
    protected $table = 'rsp_volunteer_three_month_1';
    protected $fillable = [];
    
    public function survey()
    {
        return $this->belongsTo(class_survey());
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'respondent_id');
    }

    // public function getCurationEffortAttribute()
    // {
    //     return CurationActivity::find(json_decode($this->attributes['curation_effort']))->pluck('name');
    // }

    // public function getExpertPanelsAttribute()
    // {
    //     return ExpertPanel::find(json_decode($this->attributes['expert_panels']))->pluck('name');
    // }

    // public function getHighestEdAttribute()
    // {
    //     return $this->getValueFromOptions('highest_ed');
    // }

    // public function getsatisfactionAttribute()
    // {
    //     return $this->getValueFromOptions('satisfaction');
    // }

    // public function getTrainingClearAttribute()
    // {
    //     return $this->getValueFromOptions('training_clear');
    // }

    // public function getTrainingSufficientAttribute()
    // {
    //     return $this->getValueFromOptions('training_sufficient');
    // }

    // public function getSeekAdtlTrngAttribute()
    // {
    // }

    // private function getValueFromOptions($attribute)
    // {
    //     return ($this->survey->document->questions[$attribute]->getOptionByValue($this->attributes[$attribute]))
    //         ? $this->survey->document->questions[$attribute]->getOptionByValue($this->attributes[$attribute])->label
    //         : null;
    // }
}
