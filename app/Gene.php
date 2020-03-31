<?php

namespace App;

use App\Assignment;
use Backpack\CRUD\CrudTrait;
use App\Traits\AssignableTrait;
use App\Contracts\AssignableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\Relation;

class Gene extends Model implements AssignableContract
{
    use CrudTrait, AssignableTrait;

    public $fillable = [
        'symbol',
        'hgnc_id',
        'protocol_path',
        'protocol_filename',
        'hypothesis_group',
        'hypothesis_group_url'
    ];

    public $appends = [
        'name'
    ];

    public function canBeAssignedToBaseline(): bool
    {
        return true;
    }

    public function setProtocolPathAttribute($value)
    {
        $attribute_name = 'protocol_path';
        $disk = 'public';
        $destination_path = 'gene_protocols';

        if (is_null($value) && !empty($this->attributes['protocol_path'])) {
            $this->attributes['protocol_path'] = $value;
            $this->protocol_filename = null;
            return;
        }

        if (app()->environment('testing')) {
            $this->attributes['protocol_path'] = $value;
            return;
        }

        $this->protocol_filename = $value->getClientOriginalName();

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
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
