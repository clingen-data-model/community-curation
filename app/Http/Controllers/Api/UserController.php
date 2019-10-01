<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function currentUser()
    {
        $user = \Auth::guard('api')->user();
        $user->load('roles', 'permissions');
        $user->permissions = $user->getAllPermissions();

        return $user;
    }
}
