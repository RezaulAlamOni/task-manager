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
    protected $HomeController;

    public function __construct()
    {
        $this->HomeController = new HomeController;
        $this->middleware('auth');
    }
    
    public function AddPriority (Request $request)
    {

        $ids = $request->ids;
        $priority = $request->priority;
        foreach ($ids as $id) {
            try {
                $pr = '';
                DB::beginTransaction();
                Task::where('id', $id)->update(['priority_label' => $priority]);
                DB::commit();
                if ( $priority == '1') {
                    $pr = 'Low';
                }
                if ( $priority == '2') {
                    $pr = 'Medium';
                }
                if ( $priority == '3') {
                    $pr = 'High';
                }
                $task = Task::find($id);
                $mailData = [
                    'subject'       => "Priority added to a task",
                    'body'          => "Priority ( ".$pr." ) added to a task (".$task->title.").",
                    'email'         => "email_whenAddedToTask",
                    'generalBody'   => "Priority ( ".$pr." ) added to a task (".$task->title.") ",
                    'task_id'       => $request->task_id
                ];
                $this->HomeController->userMail( (object) $mailData);
                $log_data = [
                    'project_id'=>$task->project_id,
                    'task_id' => $id,
                    'title' => $task->title,
                    'log_type' => ($priority !== null) ? 'Add '.$priority.' Priority' : 'Remove Priority',
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
