<?php

namespace App;

use Sirs\Surveys\Models\Response as SirsResponse;
use Illuminate\Database\Eloquent\Model;

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
}
