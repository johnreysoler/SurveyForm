<?php

namespace App\Http\Controllers;

use App\Models\Prerequisite;
use App\Http\Requests\StorePrerequisiteRequest;
use App\Http\Requests\UpdatePrerequisiteRequest;

class PrerequisiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StorePrerequisiteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePrerequisiteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prerequisite  $prerequisite
     * @return \Illuminate\Http\Response
     */
    public function show(Prerequisite $prerequisite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prerequisite  $prerequisite
     * @return \Illuminate\Http\Response
     */
    public function edit(Prerequisite $prerequisite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePrerequisiteRequest  $request
     * @param  \App\Models\Prerequisite  $prerequisite
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePrerequisiteRequest $request, Prerequisite $prerequisite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prerequisite  $prerequisite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prerequisite $prerequisite)
    {
        //
    }
}
