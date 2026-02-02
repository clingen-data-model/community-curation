<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyResponse extends Model
{
    use SoftDeletes;

    protected $table = 'survey_responses';

    protected $fillable = [
        'survey_slug',
        'respondent_id',
        'response_data',
        'last_page',
        'started_at',
        'finalized_at',
    ];

    protected $casts = [
        'response_data' => 'array',
        'started_at' => 'datetime',
        'finalized_at' => 'datetime',
    ];

    public function respondent()
    {
        return $this->belongsTo(User::class, 'respondent_id');
    }

    public function __get($key)
    {
        $responseData = $this->getAttributeValue('response_data');
        if (is_array($responseData) && array_key_exists($key, $responseData)) {
            return $responseData[$key];
        }

        return parent::__get($key);
    }
}
