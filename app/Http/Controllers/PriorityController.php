<?php

namespace App\Http\Controllers;

use App\ActionLog;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PriorityController extends Controller
{
    public function AddPriority (Request $request)
    {
        $ids = $request->ids;
        $priority = $request->priority;
        foreach ($ids as $id) {
            try {
                DB::beginTransaction();
                Task::where('id', $id)->update(['priority_label' => $priority]);
                DB::commit();
                $task = Task::find($id);
                $log_data = [
                    'task_id' => $id,
                    'title' => $task->title,
                    'log_type' => 'Add '.$priority.' Priority',
                    'action_type' => 'updated',
                    'action_by' => Auth::id(),
                    'action_at' => Carbon::now()
                ];
                ActionLog::create($log_data);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => 'success', 'error' => $e]);
            }
        }
        return response()->json(['status' => 'success']);
    }
}
