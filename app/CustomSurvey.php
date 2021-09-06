<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomSurvey extends Model
{
    use HasFactory;
    use RevisionableTrait;
    use CrudTrait;
    use SoftDeletes;

    public $fillable = [
        'curation_group_id',
        'volunteer_type_id',
        'name',
    ];

    public $casts = [
        'curation_group_id' => 'integer',
        'volunteer_type_id' => 'integer',
    ];
    
    public $appends = [
        'survey_url',
    ];

    /**
     * RELATIONSHIPS
     */

    /**
     * Get the curationGroup that owns the CustomSurvey
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function curationGroup(): BelongsTo
    {
        return $this->belongsTo(CurationGroup::class);
    }

    /**
     * Get the volunteerType that owns the CustomSurvey
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function volunteerType(): BelongsTo
    {
        return $this->belongsTo(VolunteerType::class);
    }

    public static function findByNameOrFail($name)
    {
        return static::where('name', $name)->sole();
    }

    /**
     * DOMAIN
     */

    public function getSurveyUrlAttribute(): String
    {
        return url('/apply/group/'.$this->name);
    }
}
