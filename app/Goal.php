<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use App\Traits\HasOtherOption;

class Goal extends Model
{
    use SoftDeletes;
    use RevisionableTrait;
    use HasOtherOption;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'name',
        'active'
    ];
}
