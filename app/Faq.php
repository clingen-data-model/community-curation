<?php

namespace App;

// use App\Traits\TranscodesHtmlToMarkdown;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Faq extends Model
{
    use SoftDeletes;
    use RevisionableTrait;
    use CrudTrait;
    // use TranscodesHtmlToMarkdown;

    protected $revisionCreationsEnabled = true;

    public $fillable = [
        'question',
        'answer'
    ];

    // public function setAnswerAttribute($value)
    // {
    //     $this->attributes['answer'] = $this->htmlToMarkdown($value);
    // }

    // public function getAnswerAttribute()
    // {
    //     return $this->markdownToHtml($this->attributes['answer']);
    // }
}
