<?php

namespace App;

use App\Contracts\AssignableContract;
use App\Traits\AssignableTrait;
use Illuminate\Database\Eloquent\Model;

class Gene extends Model implements AssignableContract
{
    use AssignableTrait;

    public $fillable = [
        'symbol',
        'protocol_path',
        'protocol_filename',
        'hypothesis_group',
        'hypothesis_group_url',
    ];

    public $appends = [
        'name',
    ];

    public function canBeAssignedToBaseline(): bool
    {
        return true;
    }

    public function getNameAttribute()
    {
        return $this->symbol;
    }

    public function getParentAssignable(): AssignableContract
    {
        return CurationActivity::where('name', 'Baseline')->first();
    }
}
