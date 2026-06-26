<?php

namespace App;

use App\Contracts\AssignableContract;
use App\Traits\AssignableTrait;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Gene extends Model implements AssignableContract
{
    use CrudTrait;
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

    public function setProtocolPathAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['protocol_path'] = null;
            $this->attributes['protocol_filename'] = null;

            return;
        }

        if (app()->environment('testing')) {
            $this->attributes['protocol_path'] = $value;

            return;
        }

        $this->protocol_filename = $value->getClientOriginalName();

        $this->uploadFileToDisk($value, 'protocol_path', 'public', 'gene_protocols');
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
