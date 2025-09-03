<?php

namespace App;

use Sirs\Surveys\Models\Response as SirsResponse;
use Sirs\Surveys\Revisions\Revision;

class SurveyResponse extends SirsResponse
{

    protected $casts = [
        'finalized_at' => 'datetime',
    ];

    public function getDataAttributes()
    {
        $data = [];
        $data['id'] = $this->id;
        $data['respondent'] = ($this->respondent) ? $this->respondent->full_name.' - id: '.$this->respondent->id : null;
        $dataCols = $this->getDataAttributeNames();
        foreach ($dataCols as $column) {
            $data[$column] = $this->{$column};
        }

        return $data;
    }

    public function revisionHistory()
    {
        // return $this->morphMany('\Venturecraft\Revisionable\Revision', 'revisionable');
        return $this->hasMany(Revision::class, 'response_id')
            ->where('response_table', '=', $this->getTable());
    }

    public function scopeFinalized($query)
    {
        return $query->whereNotNull('finalized');
    }
}
