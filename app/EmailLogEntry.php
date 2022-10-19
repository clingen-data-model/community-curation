<?php

namespace App;

use App\DbMailLog\Models\Email;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class EmailLogEntry extends Email
{
    use CrudTrait;
}
