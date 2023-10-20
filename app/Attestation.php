<?php

namespace App;

use App\Events\AttestationCreated;
use App\Events\AttestationSigned;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Event;
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
        'signed_at',
        'data',
    ];

    protected $dispatchesEvents = [
        'created' => AttestationCreated::class,
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'data' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            if ($model->isDirty('signed_at') && (! isset($model->getOriginal()['signed_at']) || is_null($model->getOriginal()['signed_at']))) {
                Event::dispatch(new AttestationSigned($model));
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

    public function userAptitude()
    {
        return $this->hasOne(UserAptitude::class);
    }

    public function scopeSigned($query)
    {
        return $query->whereNotNull('signed_at');
    }

    public function scopeUnsigned($query)
    {
        return $query->whereNull('signed_at');
    }

    public function isSigned()
    {
        return ! is_null($this->signed_at);
    }
}
