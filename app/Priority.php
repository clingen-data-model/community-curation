<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $fillable = [
        'priority_order',
        'user_id',
        'curation_activity_id',
        'expert_panel_id',
        'activity_experience',
        'activity_experience_details',
        'effort_experience',
        'effort_experience_details',
        'prioritization_round',
        'survey_id',
        'response_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function curationActivity()
    {
        return $this->belongsTo(CurationActivity::class);
    }
    
    public function expertPanel()
    {
        return $this->belongsTo(ExpertPanel::class);
    }
}
