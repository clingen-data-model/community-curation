<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'rsp_application_1';

    public function respondent()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->respondent();
    }
}
