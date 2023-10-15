<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

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

    public function scopeFinalized($query)
    {
        return $query->whereNotNull('finalized_at');
    }

    public function scopeNoUserData($query)
    {
        return $query->whereNull('respondent_id')
            ->whereNull('first_name')
            ->whereNull('last_name')
            ->whereNull('institution')
            ->whereNull('orcid_id')
            ->whereNull('street1')
            ->whereNull('street2')
            ->whereNull('city')
            ->whereNull('state')
            ->whereNull('zip')
            ->whereNull('country_id')
            ->whereNull('email')
            ->whereNull('hypothesis_id');
    }

    public function scopeHasRespondent($query)
    {
        return $query->whereHasMorph('respondent', [User::class], function ($query) {
            $query->whereNull('deleted_at');
        })
            ->whereNotNull('respondent_type')
            ->whereNotNull('respondent_id');
    }
}
