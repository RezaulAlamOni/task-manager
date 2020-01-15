<?php

namespace App\Http\Controllers;

use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportEmailController extends Controller
{
    /**
     * Daily Report Email Send
     *
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function daily($project_id = null)
    {
        $emailData = array(
            'subject' => 'Daily Report'
        );
        $today = Carbon::today()->format('Y-m-d');
        $today = '2020-01-14';
        $dateRange = array(
            "$today 00:00:00",
            "$today 23:59:59"
        );
        $date = $today;
        $project = Project::with(['action_log' => function ($q) use ($dateRange) {
            $q->whereBetween('action_at', $dateRange)
                ->with('user');
        }, 'team'])->whereHas('action_log', function ($q) {
            $q->whereBetween('action_at', ['2020-01-14 00:00:00', '2020-01-14 23:59:59']);
        })->first();
        $emailData['name'] = $project->team->team_users()->first()->name;
        return view('vendor.emailTemplates.reportTemplate', compact('emailData', 'project', 'date'));
    }
}
