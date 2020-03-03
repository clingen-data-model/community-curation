<?php

namespace App;

use App\Campaign;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'rsp_application_1';
    protected $fillable = [];

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
}
