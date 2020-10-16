<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Yugen\DbMailLog\Models\Email;

class EmailLogEntry extends Email
{
    use CrudTrait;
}
