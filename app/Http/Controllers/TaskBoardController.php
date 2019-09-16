<?php

namespace App\Http\Controllers;

use App\TaskBoard;
use Illuminate\Http\Request;

class TaskBoardController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TaskBoard  $taskBoard
     * @return \Illuminate\Http\Response
     */
    public function show(TaskBoard $taskBoard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaskBoard  $taskBoard
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskBoard $taskBoard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskBoard  $taskBoard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskBoard $taskBoard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskBoard  $taskBoard
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskBoard $taskBoard)
    {
        //
    }
}