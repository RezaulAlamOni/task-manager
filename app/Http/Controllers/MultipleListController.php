<?php

namespace App\Http\Controllers;

use App\Multiple_list;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultipleListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $actionLog;
    public function __construct()
    {
        date_default_timezone_set('UTC');
        $this->actionLog =new ActionLogController;
        $this->middleware('auth');
    }

    public function index()
    {

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
            'nav_id' => $request->nev_id,
            'list_title' => $request->name,
            'description' => $request->description,
            'created_at' => Carbon::today(),
        ]);
        $multiple_list = Project::with('multiple_list')->findOrFail($request->project_id);
        $multiple_list = $multiple_list->multiple_list;
        $log_data = [
            'multiple_list_id'=>$id->id,
            'title'=>$request->name,
            'log_type'=>'Create list',
            'action_type'=>'created',
            'action_by'=>Auth::id(),
            'action_at'=>Carbon::now()
        ];
        $this->actionLog->store($log_data);

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
