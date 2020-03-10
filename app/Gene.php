<?php

namespace App;

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
        'hypothesis_group'
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
        if (app()->environment('testing')) {
            $this->attributes['protocol_path'] = $value;
            return;
        }

        $attribute_name = 'protocol_path';
        $disk = 'public';
        $destination_path = 'gene_protocols';

        $this->protocol_filename = $value->getClientOriginalName();

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function getNameAttribute()
    {
        return $this->symbol;
    }
}
