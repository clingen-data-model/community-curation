<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationReportRow extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_type', 
        'application_id', 
        'user_id', 
        'version', 
        'data', 
        'finalized_at'
    ];

    protected $casts = [
        // 'data' => 'array',
        'finalized_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Using mutator instead of `casts` b/c MySQL JSON column does maintain key order
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    // Using accessor instead of `casts` b/c MySQL JSON column does maintain key order
    public function getDataAttribute()
    {
        return json_decode($this->attributes['data'], true);
    }


}
