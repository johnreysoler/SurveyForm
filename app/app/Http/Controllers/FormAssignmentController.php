<?php

namespace App\Http\Controllers;

use App\Models\FormAssignment;
use App\Http\Requests\StoreFormAssignmentRequest;
use App\Http\Requests\UpdateFormAssignmentRequest;

class FormAssignmentController extends Controller
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
     * @param  \App\Http\Requests\StoreFormAssignmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFormAssignmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormAssignment  $formAssignment
     * @return \Illuminate\Http\Response
     */
    public function show(FormAssignment $formAssignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormAssignment  $formAssignment
     * @return \Illuminate\Http\Response
     */
    public function edit(FormAssignment $formAssignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFormAssignmentRequest  $request
     * @param  \App\Models\FormAssignment  $formAssignment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFormAssignmentRequest $request, FormAssignment $formAssignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormAssignment  $formAssignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormAssignment $formAssignment)
    {
        //
    }
}
