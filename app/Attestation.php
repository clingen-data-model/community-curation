<?php

namespace App;

use App\Events\AttestationSigned;
use App\Events\AttestationCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Attestation extends Model
{
    use RevisionableTrait;
    use SoftDeletes;

    public $revisionCreationEnabled = true;
    protected $fillable = [
        'aptitude_id',
        'user_id',
        'assignment_id',
        'signed_at'
    ];
    protected $dates = [
        'signed_at'
    ];

    protected $dispatchesEvents = [
        'created' => AttestationCreated::class
    ];

    public static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            if ($model->isDirty('signed_at') && (!isset($model->getOriginal()['signed_at']) || is_null($model->getOriginal()['signed_at']))) {
                \Event::dispatch(new AttestationSigned($model));
            }
        });
    }

    public function aptitude()
    {
        return $this->belongsTo(Aptitude::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function scopeSigned($query)
    {
        return $query->whereNotNull("signed_at");
    }

    public function scopeUnsigned($query)
    {
        return $query->whereNull("signed_at");
    }
    
    public function isSigned()
    {
        return !is_null($this->signed_at);
    }
    
    

}