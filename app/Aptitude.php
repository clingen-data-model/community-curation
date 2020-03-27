<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class Aptitude extends Model
{
    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name',
        'training_materials_url',
        'subject_type',
        'subject_id',
        'volunteer_type_id',
        'is_primary',
        'evaluator_class'
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_aptitudes')
            ->withPivot([
                'assignment_id',
                'attestation_id',
                'trained_at',
                'granted_at',
            ])
            ->withTimestamps();
    }

    public function scopeForSubject($query, $subjectClass, $subjectId)
    {
        return $query->where([
            'subject_type' => $subjectClass,
            'subject_id' => $subjectId
        ]);
    }
    
    public function isBasic()
    {
        return $this->attributes['is_primary'];
    }
}
