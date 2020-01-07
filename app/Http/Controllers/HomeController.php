<?php

namespace App\Http\Controllers;

use App\Project;
use App\Team;
use App\User;
use App\AssignedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\UserMail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        // $this->middleware('subscribed');

        // $this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function show()
    {
        $team_id = Auth::user()->current_team_id;
        $Projects = Project::where('team_id', $team_id)->get();


        return view('home',['projects' => $Projects]);
    }

    public function allComment()
    {
        $task_comment = AssignedUser::where('user_id', Auth::user()->id)->with('taskComment')->get();
        // dd($task_comment);
        $commentsArr = [];
        foreach($task_comment as $key => $assignuser)
        {   $j = 0;
            if(count($assignuser['taskComment']) > 0)
            {
                foreach($assignuser['taskComment'] as $keys => $comments)
                {
                    $commentsArr[] =  $comments;
                }
            }
        }
        return response()->json(['success' => true, 'data' => $commentsArr]);
    }

    public function AuthUser(){
        $user = Auth::user();
        return response()->json(['user'=>$user]);
    }

    public function userMail(Request $request)
    {   
        $emails = [];
        if (isset($request->task_id) && $request->task_id !== '') {
            $users = AssignedUser::where('task_id',$request->task_id)->with(['users'])->get();
            foreach ($users as $key => $value) {
                $emails[] = $value->users->email;
            }
        }
        if (isset($request->user_id) && $request->user_id !== '') {
            $user = User::where('id',$request->user_id)->first();
            $emails[] = $user->email;
        }
        if (count($emails) > 0) {
            // $comment = 'Hi, Comment Add to card.';
            $comment['subject'] = $request->subject;
            $comment['body'] = $request->body;
            Mail::to($emails)->send(new UserMail($comment));
        }
    }
}
