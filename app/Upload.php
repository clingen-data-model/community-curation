<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use App\Scopes\CuratorUploadScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Upload extends Model
{
    use SoftDeletes;
    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;

    protected $fillable = [
        'user_id',
        'name',
        'notes',
        'file_name',
        'file_path',
        'upload_category_id',
        'uploader_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CuratorUploadScope());

        static::creating(function ($upload) {
            if (! $upload->uploader_id && Auth::user()) {
                $upload->uploader_id = Auth::user()->id;
            }
        });

        static::forceDeleted(function ($upload) {
            unlink(storage_path('/app/'.$upload->file_path));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function category()
    {
        return $this->belongsTo(UploadCategory::class, 'upload_category_id');
    }

    public function scopeForUser($query, $user)
    {
        if (is_int($user)) {
            return $query->where('user_id', $user);
        }

        if (is_object($user) && $user::class == User::class) {
            return $query->where('user_id', $user->id);
        }
    }

    public function scopeWithCategory($query, $category)
    {
        if (is_int($category)) {
            return $query->where('category_id', $category);
        }

        if (is_object($category) && $category::class == UploadCategory::class) {
            return $query->where('category_id', $category->id);
        }
    }
}
