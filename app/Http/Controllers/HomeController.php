<?php

namespace App\Http\Controllers;

use App\Project;
use App\Team;
use App\AssignedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
