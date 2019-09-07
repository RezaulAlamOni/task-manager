<?php

namespace App\Http\Controllers;

use App\Multiple_list;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MultipleListController extends Controller
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Multiple_list::create([
            'project_id' => $request->project_id,
            'list_title' => $request->name,
            'description' => $request->description,
            'created_at' => Carbon::today(),
        ]);
        $multiple_list = Project::with('multiple_list')->findOrFail($request->project_id);
        $multiple_list = $multiple_list->multiple_list;
        return response()->json(['multiple_list' => $multiple_list,'id'=>$id]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Multiple_list $multiple_list
     * @return \Illuminate\Http\Response
     */
    public function show(Multiple_list $multiple_list)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Multiple_list $multiple_list
     * @return \Illuminate\Http\Response
     */
    public function edit(Multiple_list $multiple_list)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Multiple_list $multiple_list
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Multiple_list $multiple_list)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Multiple_list $multiple_list
     * @return \Illuminate\Http\Response
     */
    public function destroy(Multiple_list $multiple_list)
    {
        //
    }
}
