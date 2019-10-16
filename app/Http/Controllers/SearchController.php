<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function suggestUser(Request $request){
        if(isset($request->user_name)  && trim($request->user_name) != ''){
            $team_id = DB::table('team_users')->where('user_id', Auth::id())->first();
            $search_user= User::join('team_users', 'team_users.user_id', 'users.id')->where('team_users.team_id', $team_id->team_id)
                ->Where('name', 'like', '%' . $request->user_name . '%')
                ->orWhere('email', 'like', '%' . $request->user_name . '%')
                ->get();
            return response()->json(['search_user'=>$search_user]);
        }else if (isset($request->text) && trim($request->text) != '') {
            $task = Task::where('project_id',$request->project_id)
                ->where('title', 'like', '%' . $request->text . '%')->get();
            return response()->json(['search_tasks'=>$task]);
        } else if (isset($request->user_id) && trim($request->user_id) != '') {
            $task = Task::join('task_assigned_users','task_lists.id','task_assigned_users.task_id')
                ->select('task_lists.*')
                ->where('project_id', $request->p_id)
                ->where('task_assigned_users.user_id',$request->user_id)
                ->get();
            return response()->json(['search_tasks' => $task]);
        }

    }
}