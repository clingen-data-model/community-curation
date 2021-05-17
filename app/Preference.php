<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Preference extends Model
{
    public $fillable = [
        'name',
        'default',
        'data_type',
        'description',
        'applies_to_volunteer',
        'applies_to_user',
    ];

    private $validDataTypes = ['boolean', 'integer', 'string', 'float', 'array', 'object'];

    public function scopeNameIs($query, $name)
    {
        return $query->where('name', $name);
    }

    public static function findByName($name)
    {
        $preference = static::nameIs($name)->first();
        if (!$preference) {
            throw new InvalidArgumentException('Unknown preference name: '.$name);
        }
        return $preference;
    }

    public function setDataTypeAttribute($value)
    {
        $value = ($value == 'int') ? 'integer' : $value;

        if (!in_array($value, $this->validDataTypes)) {
            throw new InvalidArgumentException('Bad data type given for preference: '.$value);
        }

        $this->attributes['data_type'] = $value;
    }
}
