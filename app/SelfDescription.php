<?php

namespace App;

use App\Traits\HasOtherOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class SelfDescription extends Model
{
    use SoftDeletes;
    use RevisionableTrait;
    use HasOtherOption;

    protected $revisionCreationsEnabled = true;
    
    protected $fillable = ['name'];
}
