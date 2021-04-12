<?php

namespace App;

use App\Traits\HasOtherOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Campaign extends Model
{
    use SoftDeletes;
    use RevisionableTrait;
    use HasOtherOption;
    use CrudTrait;

    protected $revisionCreationsEnabled = true;
    protected $fillable = [
        'name',
        'active',
        'starts_at',
        'ends_at',
        'display_order'
    ];
    protected $dates = [
        'starts_at',
        'ends_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('display_order', 'asc');
        });
    }
}
