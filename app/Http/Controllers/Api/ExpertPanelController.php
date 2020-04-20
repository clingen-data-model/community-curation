<?php

namespace App\Http\Controllers\Api;

use App\ExpertPanel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DefaultResource;

class ExpertPanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return DefaultResource::collection(ExpertPanel::orderBy('name')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new DefaultResource(ExpertPanel::find($id));
    }
}
