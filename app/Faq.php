<?php

namespace App;

// use App\Traits\TranscodesHtmlToMarkdown;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Faq extends Model
{
    use SoftDeletes;
    use RevisionableTrait;
    // use TranscodesHtmlToMarkdown;

    protected $revisionCreationsEnabled = true;

    public $fillable = [
        'question',
        'answer',
        'screenshots',
    ];

    public $casts = [
        'screenshots' => 'array',
    ];
}
