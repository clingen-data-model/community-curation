<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Followup3MonthVolunteer extends Model
{
    protected $table = 'rsp_threemonthvolunteerfollowup_1';
    
    public function volunteer()
    {
        return $this->belongsTo(User::class, 'respondent_id');
    }
}
