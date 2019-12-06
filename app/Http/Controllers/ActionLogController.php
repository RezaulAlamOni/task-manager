<?php

namespace App\Http\Controllers;

use App\ActionLog;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActionLogController extends Controller
{
    public function __construct()
    {
    }

    public function getSingleTaskLog($task_id)
    {
        $log = ActionLog::where('task_id', $task_id)->orderBy('id','desc')->get();
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
        foreach ($all_logs as $log) {
            if ($log->action_type == 'softdelete' && $log->action_at > Carbon::now()->subDays(30) ){
                $log->undo = 1;
            }else{
                $log->undo = 0;
            }
        }
        return \response()->json(['logs'=>$all_logs,'status'=>'success']);
    }

    public function UndoAction(Request $request){
        isset($request->type) ? $undo_type = $request->type : $undo_type = null;
        isset($request->id) ? $log_id = $request->id : $log_id = null;
        if($undo_type == 'delete' && $log_id != null){
            $log = ActionLog::find($log_id);
//            $keys = array_keys(array_filter($log, function($v) { if($v !== null){ return $v;}}));

            if ($log->task_id != null){
                $task_id = $log->task_id;
                if (Task::where('id',$task_id)->update(['is_deleted'=>0,'deleted_at'=>Carbon::now()])){
                    ActionLog::where('id',$log_id)->delete();
                }

                return response()->json(['status'=>true]);
            }
        }

    }

    public function store($req = [])
    {
        if ($req) {
            ActionLog::create($req);
        }
    }
}
