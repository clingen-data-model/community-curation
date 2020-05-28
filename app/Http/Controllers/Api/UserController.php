<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function currentUser()
    {
        $user = \Auth::guard('api')->user();
        $user->load('roles', 'permissions');
        $user->permissions = $user->getAllPermissions();

        return $user;
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
                return User::with('roles')->get()->filter(function ($user) {
                    return $user->canBeImpersonated();
                });
            });
        }
        return $impersonatable;
    }
}
