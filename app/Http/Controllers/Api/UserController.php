<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrentUserResource;
use App\Http\Resources\UserResource;
use App\Services\Search\UserSearchService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    protected $searchService;

    public function __construct(UserSearchService $userSearch)
    {
        $this->searchService = $userSearch;
    }

    public function index(Request $request)
    {
        $pageSize = ($request->has('perPage') && !is_null($request->perPage)) ? $request->perPage : 25;

        $query = $this->searchService->buildQuery($request->all());

        $users = ($request->has('page'))
                        ? $query->paginate($pageSize)
                        : $query->get();

        return UserResource::collection($users);
    }

    public function currentUser()
    {
        $user = \Auth::guard('api')->user();
        $user->load('roles', 'permissions', 'preferences');
        $user->permissions = $user->getAllPermissions();

        return new CurrentUserResource($user);
    }

    public function impersonatableUsers()
    {
        $userModel = Auth::user();
        $userModel->load(['roles']);
        $user = $userModel->toArray();
        $user['permissions'] = $userModel->getAllPermissions()->toArray();
        $user['panel_summary'] = $userModel->panel_summary;

        $impersonatable = collect();
        if (Auth::user()->canImpersonate()) {
            $impersonatable = Cache::remember('impersonatable-users', 60, function () {
                return User::with('roles')->orderBy('first_name')->get()->filter(function ($user) {
                    return $user->canBeImpersonated();
                });
            });
        }

        return $impersonatable;
    }
}
