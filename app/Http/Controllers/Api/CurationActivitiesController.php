<?php

namespace App\Http\Controllers\Api;

use App\CurationActivity;
use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultResource;
use Illuminate\Http\Request;

class CurationActivitiesController extends Controller
{
    public function index(Request $request)
    {
        $query = CurationActivity::query();
        if ($request->with_baseline) {
            return DefaultResource::collection($query->get());
        }

        if ($request->only_baseline) {
            return DefaultResource::collection($query->geneType()->get());
        }

        return Defaultresource::collection($query->curationGroupType()->get());
    }
}
