<?php

namespace App\Http\Controllers;

use App\ActionLog;

class ActionLogController extends Controller
{
    public function __construct()
    {
    }

    public function getSingleTaskLog($task_id)
    {
        $log = ActionLog::where('task_id', $task_id)->get();
        return response()->json($log);
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store($req = [])
    {
        if ($req) {
            ActionLog::create($req);
        }
    }
}
