<?php

namespace App\Http\Controllers\Api;

use App\ExpertPanel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpertPanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ExpertPanel::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExpertPanel  $expertPanel
     * @return \Illuminate\Http\Response
     */
    public function show(ExpertPanel $expertPanel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExpertPanel  $expertPanel
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpertPanel $expertPanel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExpertPanel  $expertPanel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpertPanel $expertPanel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExpertPanel  $expertPanel
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpertPanel $expertPanel)
    {
        //
    }
}
