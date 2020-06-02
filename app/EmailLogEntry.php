<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Yugen\DbMailLog\Models\Email;
use Illuminate\Database\Eloquent\Model;

class EmailLogEntry extends Email
{
    use CrudTrait;
}
