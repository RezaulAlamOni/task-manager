<?php

namespace App\Http\Controllers;

use App\Multiple_board;
use App\Multiple_list;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultipleBoardController extends Controller
{
    protected $actionLog;
    public function __construct()
    {
        date_default_timezone_set('UTC');
        $this->actionLog =new ActionLogController;
        $this->middleware('auth');
    }

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Multiple_board::create([
            'project_id' => $request->project_id,
            'nav_id' => $request->nev_id,
            'board_title' => $request->name,
            'description' => $request->description,
            'created_at' => Carbon::today(),
        ]);
        $multiple_board = Project::findOrFail($request->project_id);
        $multiple_board = $multiple_board->multiple_board;
        $log_data = [
            'multiple_board_id'=>$id->id,
            'title'=>$request->name,
            'log_type'=>'Create board',
            'action_type'=>'created',
            'action_by'=>Auth::id(),
            'action_at'=>Carbon::now()
        ];
        $this->actionLog->store($log_data);

        return response()->json(['multiple_board' => $multiple_board,'id'=>$id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Multiple_board  $multiple_board
     * @return \Illuminate\Http\Response
     */
    public function show(Multiple_board $multiple_board)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Multiple_board  $multiple_board
     * @return \Illuminate\Http\Response
     */
    public function edit(Multiple_board $multiple_board)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Multiple_board  $multiple_board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Multiple_board $multiple_board)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Multiple_board  $multiple_board
     * @return \Illuminate\Http\Response
     */
    public function destroy(Multiple_board $multiple_board)
    {
        //
    }
}
