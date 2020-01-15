<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ReportEmailController extends Controller
{
    /**
     * Daily Report Email Send
     *
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function daily($project_id)
    {
        $emailData = array(
            'subject' => 'Daily Report'
        );
        $project = Project::with(['action_log' => function ($q) {
            $q->whereBetween('action_at', ['2020-01-14 00:00:00', '2020-01-14 23:59:59'])
                ->with('user');
        }, 'team'])->whereHas('action_log', function ($q) {
            $q->whereBetween('action_at', ['2020-01-14 00:00:00', '2020-01-14 23:59:59']);
        })->first();
        return view('vendor.emailTemplates.reportDailyTemplate', compact('emailData', 'project'));
    }
}
