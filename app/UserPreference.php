<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class UserPreference extends Model
{
    public $fillable = [
        'user_id',
        'preference_id',
        'value',
    ];

    public function preference()
    {
        return $this->belongsTo(Preference::class);
    }

    public function getValueAttribute()
    {
        if ($this->attributes['value'] === null) {
            return null;
        }

        switch ($this->preference->data_type) {
            case 'array':
                return json_decode($this->attributes['value'], true);
            case 'boolean':
                return (bool) $this->attributes['value'];
            case 'float':
                return (float) $this->attributes['value'];
            case 'integer':
                return (int) $this->attributes['value'];
            case 'object':
                return json_decode($this->attributes['value'], false);
            case 'string':
                return (string) $this->attributes['value'];
            default:
                throw new Exception('Encountered unkown preference data type while casting userPrefrence value.');
        }
    }

    public function setValueAttribute($val)
    {
        if ($val === null && $this->preference->default) {
            $val = $this->preference->default;
        }

        switch ($this->preference->data_type) {
            case 'array':
                $this->attributes['value'] = json_encode((array) $val);
                break;
            case 'boolean':
                $this->attributes['value'] = (bool) $val;
                break;
            case 'float':
                $this->attributes['value'] = (float) $val;
                break;
            case 'integer':
                $this->attributes['value'] = (int) $val;
                break;
            case 'object':
                $this->attributes['value'] = json_encode((object) $val);
                break;
            case 'string':
                $this->attributes['value'] = (string) $val;
                break;
            default:
                throw new Exception('Encountered unkown preference data type while casting userPrefrence value.');
        }
    }

    public function scopeForPreference($query, $preference)
    {
        if (is_object($preference) && $preference::class == Preference::class) {
            return $query->where('preference_id', $preference->id);
        }
        if (is_numeric($preference)) {
            return $query->where('preference_id', $preference);
        }
        if (is_string($preference)) {
            return $query->whereHas('preference', function ($q) use ($preference) {
                $q->where('name', $preference);
            });
        }

        throw new InvalidArgumentException('Unknown preference passed to '.__METHOD__);
    }
}
