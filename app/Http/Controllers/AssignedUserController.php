<?php

namespace App\Http\Controllers;

use App\AssignedUser;
use App\User;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignedUserController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $checkIsUserAssigned = AssignedUser::where([
            'task_id' => $request->task_id,
            'user_id' => $request->user_id
        ])->count();
        if ($checkIsUserAssigned <= 0) {
            $assignedUser = AssignedUser::create([
                'task_id'    => $request->task_id,
                'user_id'    => $request->user_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // $user = User::where('id',$request->user_id)->first();
            // $task = Task::where('id',$request->task_id)->with(['project'])->first();
            // // $comment = 'Hi, A new task is assigned to you.';
            // $comment['subject'] = "A new task is assigned to you";
            // $comment['body'] = "A new task ( <strong>".$task->title."</strong> from <strong>".$task->project->name."</strong> project ) is assigned to you";
            // Mail::to($user->email)->send(new UserMail($comment));

            $data =  AssignedUser::join('users', 'task_assigned_users.user_id','users.id')->where('task_id',  $request->task_id)->get()->toArray();
            return response()->json(['success' => 'success', 'data' => $data[0]]);
        } else {
            return response()->json('already added');
        }

    }

    public function delete(Request $request)
    {
        AssignedUser::where($request->all())->delete();
        // $user = User::where('id',$request->user_id)->first();
        // $comment['subject'] = "You have been removed from a task";
        // $comment['body'] = "You have been removed from a task";
        // Mail::to($user->email)->send(new UserMail($comment));

        // Mail::to($request->user())->queue(new OrderShipped($order));
        return response()->json('success');
    }


    public function edit(AssignedUser $assignedUser)
    {
        //
    }

    public function update(Request $request, AssignedUser $assignedUser)
    {
        //
    }

    public function destroy(AssignedUser $assignedUser)
    {
        //
    }
}
