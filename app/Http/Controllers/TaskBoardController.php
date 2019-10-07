<?php

namespace App\Http\Controllers;

use App\TaskBoard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $actionLog;

    public function __construct()
    {
        date_default_timezone_set('UTC');
        $this->actionLog = new ActionLogController;
        $this->middleware('auth');
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param TaskBoard $taskBoard
     * @return Response
     */
    public function show(TaskBoard $taskBoard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param TaskBoard $taskBoard
     * @return Response
     */
    public function edit(TaskBoard $taskBoard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param TaskBoard $taskBoard
     * @return Response
     */
    public function update(Request $request, TaskBoard $taskBoard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param TaskBoard $taskBoard
     * @return Response
     */
    public function destroy(TaskBoard $taskBoard)
    {
        //
    }
}
