<?php

namespace App\Http\Controllers;

use App\ActionLog;
use Illuminate\Http\Request;

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

    public function AllLogs(Request $request, $project_id)
    {
        $per_page = isset( $request->per_page) ? $request->per_page : 100;
        $all_logs = ActionLog::select('action_logs.*','task_lists.title','task_lists.list_id')
            ->join('task_lists','action_logs.task_id','task_lists.id')->orderBy('action_logs.id','desc')
            ->where('task_lists.project_id',$project_id)
            ->orWhere('action_logs.project_id',$project_id)
            ->with('user')
            ->paginate(($per_page != null ) ? $per_page : 100);
        return \response()->json(['logs'=>$all_logs,'status'=>'success']);
    }

    public function store($req = [])
    {
        if ($req) {
            ActionLog::create($req);
        }
    }
}
