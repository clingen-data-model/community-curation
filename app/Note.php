<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Note extends Model
{
    use SoftDeletes;
    use RevisionableTrait;

    public $revisionCreationsEnabled = true;

    public $fillable = [
        'notable_type',
        'notable_id',
        'content',
        'created_by_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function notable()
    {
        return $this->morphTo();
    }
}
