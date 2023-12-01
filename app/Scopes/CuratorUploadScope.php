<?php

namespace App\Scopes;

use App\Upload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CuratorUploadScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (! Auth::user()->can('viewAny', Upload::class)) {
            $builder->where('user_id', Auth::user()->id);
        }
    }
}
