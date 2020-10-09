<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $fillable = [
        'priority_order',
        'user_id',
        'curation_activity_id',
        'curation_group_id',
        'activity_experience',
        'activity_experience_details',
        'effort_experience',
        'effort_experience_details',
        'prioritization_round',
        'outside_panel',
        'survey_id',
        'response_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function curationActivity()
    {
        return $this->belongsTo(CurationActivity::class);
    }

    public function curationGroup()
    {
        return $this->belongsTo(CurationGroup::class);
    }

    public function survey()
    {
        return $this->belongsTo(class_survey());
    }

    public function getSurveyResponseAttribute()
    {
        return $this->survey->responses()->find($this->response_id);
    }
}
