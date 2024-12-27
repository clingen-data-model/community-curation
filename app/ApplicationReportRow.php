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
        'data' => 'array',
        'finalized_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
