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
    private $weekRange;

    public function __construct()
    {
        $this->test_env = false;
        $this->show_view = false;
        $this->sendMail = true;
        $this->limitProject = 1;
        $this->weekRange = array(
            'start' => Carbon::MONDAY,
            'end' => Carbon::SUNDAY
        );
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
     * @param array $emailInfo
     * @param array $dateRange
     * @param string $date
     * @param null $project_id
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function SendEmail($emailInfo, $dateRange, $date, $project_id = null)
    {
        $success = true;
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
            if ($projects->count() > 0) {
                return $this->showEmailView($projects, $project_id, $date, 'weekly');
            } else {
                echo "<b>No Projects found:</b> $date<br/>";
                return false;
            }
        } else {
            $projects = $projects->get();
        }

        $counter = 0;
        foreach ($projects as $project) {
            foreach ($project->team->team_users as $team_user) {
                $emailData = array(
                    'type' => $emailInfo['type'],
                    'subject' => $emailInfo['subject'],
                    'name' => $team_user->name
                );
                if ($this->sendMail) {
                    Mail::to($team_user->email)->send(new ReportMail($emailData, $project, $date));
                    if (Mail::failures()) {
                        $success = false;
                    }
                    usleep(1 * 1000);
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
        return $success;
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

    /**
     * Daily Report Email Send
     *
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function weekly($project_id = null)
    {
        if ($this->test_env) {
            $today = '2020-01-20';
            $now = Carbon::parse($today)->subWeeks(1);
        } else {
            $now = Carbon::now();
        }
        $weekStartDate = $now->startOfWeek($this->weekRange['start'])->format('Y-m-d H:i:s');
        $weekEndDate = $now->endOfWeek($this->weekRange['end'])->format('Y-m-d H:i:s');
        $dateRange = array(
            $weekStartDate,
            $weekEndDate
        );
        $date = Carbon::parse($weekStartDate)->format('d F, Y');
        $date .= " To ";
        $date .= Carbon::parse($weekEndDate)->format('d F, Y');

        $emailInfo = array(
            'type' => 'weekly',
            'subject' => 'Weekly Report'
        );
        $info = $this->SendEmail($emailInfo, $dateRange, $date, $project_id);

        if ($this->show_view) { // Show view if show_view is enabled
            if ($info) {
                return $info;
            }
        } else {
            echo $date . '<br/>';
        }

        if ($info) {
            echo $emailInfo['subject'] . " Email Send Successfully";
        } else {
            echo $emailInfo['subject'] . " Email Sending Failed";
        }
    }
}
