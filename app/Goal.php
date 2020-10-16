<?php

namespace App;

use App\Traits\HasOtherOption;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Goal extends Model
{
    use SoftDeletes;
    use RevisionableTrait;
    use HasOtherOption;
    use CrudTrait;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name',
        'active',
    ];
}
