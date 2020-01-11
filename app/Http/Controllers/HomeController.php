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
// use EmailNotificationController;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $emailNotification;
    public function __construct()
    {
        $this->middleware('auth');
        $this->emailNotification = new EmailNotificationController();
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


        return view('home', ['projects' => $Projects]);
    }

    public function allComment()
    {
        $task_comment = AssignedUser::where('user_id', Auth::user()->id)->with('taskComment')->get();
        // dd($task_comment);
        $commentsArr = [];
        foreach ($task_comment as $key => $assignuser) {
            $j = 0;
            if (count($assignuser['taskComment']) > 0) {
                foreach ($assignuser['taskComment'] as $keys => $comments) {
                    $commentsArr[] =  $comments;
                }
            }
        }
        return response()->json(['success' => true, 'data' => $commentsArr]);
    }

    public function AuthUser()
    {
        $user = Auth::user();
        return response()->json(['user' => $user]);
    }

    public function userMail(Request $request)
    {
        $emails = [];
        $userIds = [];
        $mails = [];
        $emailCond = $request->email;
        if (isset($request->task_id) && $request->task_id !== '') {
            $users = AssignedUser::where('task_id', $request->task_id)->with(['users'])->get();
            foreach ($users as $key => $value) {
                // $data[$value->users->id] = $this->emailNotification->getNotificationsByUser($value->users->id);
                if (!in_array($value->users->email, $emails)) {
                    $emails[$value->users->id] = $value->users->email;
                }
                if (!in_array($value->users->id, $userIds)) {
                    $userIds[] = $value->users->id;
                }
            }
        }
        if (isset($request->user_id) && $request->user_id !== '') {
            if (is_array($request->user_id)) {
                foreach ($request->user_id as $keys => $mailuser) {
                    $user = User::where('id', $mailuser)->first();
                    if (!in_array($user->email, $emails)) {
                        $emails[$user->id] = $user->email;
                    }
                    if (!in_array($user->id, $userIds)) {
                        $userIds[] = $user->id;
                    }
                }
            } else {
                $user = User::where('id', $request->user_id)->first();
                if (!in_array($user->email, $emails)) {
                    $emails[$user->id] = $user->email;
                }
                if (!in_array($request->user_id, $userIds)) {
                    $userIds[] = $request->user_id;
                }
            }
        }
        foreach ($userIds as $keys => $ids) {
            $data = $this->emailNotification->getNotificationsByUser($ids);
            echo $data->original['email_IAmOn'];
            if ($data->original['email_IAmOn'] == 1) {
                if ($emails[$ids] !== '') {
                    // $comment = 'Hi, Comment Add to card.';
                    $comment['subject'] = $request->subject;
                    $comment['body'] = $request->body;
                    $mails[] = Mail::to($emails[$ids])->send(new UserMail($comment));
                }
            } else {
                if ($data->original[$emailCond] == 1) {
                    if ($emails[$ids] !== '') {
                        // $comment = 'Hi, Comment Add to card.';
                        $comment['subject'] = $request->subject;
                        $comment['body'] = $request->body;
                        $mails[] = Mail::to($emails[$ids])->send(new UserMail($comment));
                    }
                }
            }
        }
        // return $this->sendAllToAllUser($userIds, $request);
        return $mails;
        
        
        // emailFreq_everydayUpdate     : 0
        // emailFreq_dailyReport        : 0
        // emailFreq_weeklyReport       : 0
        // emailFreq_monthlyReport      : 0
        // emailFreq_never              : 0
        // email_IAmOn                  : 0
        // email_everything             : 0
        // email_whenAddedToTask        : 0
        // email_whenRemovedFromTask    : 0
        // email_titleChanged           : 0
        // email_descriptionUpdated     : 0
        // email_commentLeft            : 0
        // email_commentMention         : 0
        // email_taskAdded              : 0
        // email_taskUpdated            : 0
        // email_taskCommented          : 0

    }

    /**
     * Get Logged in User Profile.
     *
     * @return JsonResponse
     */
    public function profile()
    {
        $makeVisible = array(
            'country_code',
            'phone',
            'card_brand',
            'card_last_four',
            'card_country',
            'billing_address',
            'billing_address_line_2',
            'billing_city',
            'billing_zip',
            'billing_country',
            'extra_billing_information',
        );
        $user = Auth::user()->makeVisible($makeVisible);
        return response()->json(['user' => $user]);
    }

    public function sendAllToAllUser($userIds, $request)
    {   
        // $team_id = Auth::user()->current_team_id;
        // return $data = Team::where('team_id', $team_id)->with(['user'])->get();
    }
}
