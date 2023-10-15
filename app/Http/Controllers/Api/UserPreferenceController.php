<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NotImplementedException;
use App\Http\Controllers\Controller;
use App\Http\Resources\CurrentUserResource;
use App\User;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        throw new NotImplementedException();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        throw new NotImplementedException();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        throw new NotImplementedException();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        throw new NotImplementedException();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        throw new NotImplementedException();
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId, $preferenceName)
    {
        $user = User::findOrFail($userId)
            ->setPreference($preferenceName, $request->value);

        $user->load('roles', 'permissions', 'preferences');

        return new CurrentUserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
