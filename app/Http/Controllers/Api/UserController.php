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
use Illuminate\Support\Facades\DB;

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
        $currentUser = Auth::user();

        if (!$currentUser || !$currentUser->canImpersonate()) {
            return response()->json([]);
        }

        return Cache::remember('impersonatable-users:' . $currentUser->id, 60, function () use ($currentUser) {
            // Eager load all users with their roles in one query
            return User::with('roles')
                ->orderBy('first_name')
                ->get()
                ->filter(function ($user) use ($currentUser) {
                    // Run the same logic from canBeImpersonated(), inline
                    if ($user->hasRole('programmer')) {
                        return false;
                    }

                    if ($currentUser->hasRole('programmer')) {
                        return true;
                    }

                    if ($currentUser->hasRole('admin') && $user->hasAnyRole(['programmer', 'super-admin'])) {
                        return false;
                    }

                    if ($currentUser->roles->intersect($user->roles)->isNotEmpty()) {
                        return false;
                    }

                    return true;
                })
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->full_name ?? trim("{$user->first_name} {$user->last_name}"),
                    ];
                })
                ->values();
        });
    }
}
