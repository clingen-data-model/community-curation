<?php

namespace App;

use Sirs\Surveys\Revisions\Revision;
use Illuminate\Database\Eloquent\Model;
use Sirs\Surveys\Models\Response as SirsResponse;

class SurveyResponse extends SirsResponse
{
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
}
