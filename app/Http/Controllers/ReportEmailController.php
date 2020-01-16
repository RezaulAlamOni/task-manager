<?php

namespace App\Http\Controllers;

use App\Mail\ReportMail;
use App\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ReportEmailController extends Controller
{
    private $test_env;
    private $show_view;
    private $sendMail;
    private $limitProject;

    public function __construct()
    {
        $this->test_env = false;
        $this->show_view = false;
        $this->sendMail = true;
        $this->limitProject = 4;
    }

    /**
     * Show the email template view
     *
     * @param $projects
     * @param $project_id
     * @param $date
     * @param string $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function showEmailView($projects, $project_id, $date, $type = 'daily')
    {
        $project = $projects->find($project_id);
        $emailData = array(
            'type' => $type,
            'subject' => 'Daily Report',
            'name' => $project->team->team_users()->first()->name,
        );
        return view('vendor.emailTemplates.reportTemplate', compact('emailData', 'project', 'date'));
    }

    /**
     * Daily Report Email Send
     *
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function daily($project_id = null)
    {
        if ($this->test_env) {
            $today = '2020-01-14';
        } else {
            $today = Carbon::today()->format('Y-m-d');
        }
        $from = $today;
        $to = $today;
        $dateRange = array(
            "$from 00:00:00",
            "$to 23:59:59"
        );
        $date = Carbon::parse($today)->format('d F, Y');

        $projects = Project::with(['action_log' => function ($q) use ($dateRange) {
            $q->whereBetween('action_at', $dateRange)->with(['user']);
        }, 'team.team_users' => function ($q) {
            $q->with(['notifications' => function ($q) {
                $q->where('unique_id', 'emailFreq_dailyReport');
            }])->whereHas('notifications', function ($q) {
                $q->where('unique_id', 'emailFreq_dailyReport');
            });
        }])->whereHas('action_log', function ($q) use ($dateRange) {
            $q->whereBetween('action_at', $dateRange);
        });

        if ($this->show_view) { // Show view if want to debug email template
            return $this->showEmailView($projects, $project_id, $date, 'daily');
        } else {
            $projects = $projects->get();
        }

        $counter = 0;
        foreach ($projects as $project) {
            foreach ($project->team->team_users as $team_user) {
                $emailData = array(
                    'type' => 'daily',
                    'subject' => 'Daily Report',
                    'name' => $team_user->name
                );
                if ($this->sendMail) {
                    Mail::to($team_user->email)->send(new ReportMail($emailData, $project, $date));
                    sleep(1);
                }
                if ($this->test_env) {
                    echo "Test Environment Run Successfully<br/>";
                    echo "Counter Value: $counter<br/>";
                    dump($emailData, $team_user->email, $project->toArray());
                    if ($this->limitProject < $counter) {
                        break 2;
                    }
                }
            }
            $counter++;
        }
        echo "Daily Report Email Send Successfully";
    }
}
