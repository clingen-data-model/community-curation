<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Volunteer extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    use LogsActivity;
    use SoftDeletes;

    public $revisionCreationEnabled = true;

    protected $fillable = [
        'user_id',
        'volunteer_type_id',
        'volunteer_status_id',
        'email',
        'address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function volunteerType()
    {
        return $this->belongsTo(VolunteerType::class);
    }
    
    public function volunteerStatus()
    {
        return $this->belongsTo(VolunteerStatus::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }    
}
