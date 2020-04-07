<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Volunteer6MonthSurvey extends Model
{
    protected $table = 'rsp_volunteer_six_month_1';
    protected $fillable = [];
    
    public function survey()
    {
        return $this->belongsTo(class_survey());
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'respondent_id');
    }
}
