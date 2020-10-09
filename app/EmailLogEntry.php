<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Yugen\DbMailLog\Models\Email;

class EmailLogEntry extends Email
{
    use CrudTrait;
}
