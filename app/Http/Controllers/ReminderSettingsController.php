<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReminderSettingsController extends Controller
{
    /**
     * Send Due Date Reminder Email
     */
    public function sendDueDateEmail()
    {
        $user = User::whereHas('tasks', function ($q) {
            $q->whereDate('date', Carbon::today()->addDays(2)->format("Y-m-d"));
        })->whereHas('notifications', function ($q) {
            $q->where('unique_id', 'reminder_whenTaskIsDue');
        })->with(['tasks' => function ($q) {
            $q->whereDate('date', Carbon::today()->addDays(2)->format("Y-m-d"));
        }, 'notifications' => function ($qr) {
            $qr->where('unique_id', 'reminder_whenTaskIsDue');
        }])->get();
        return $user;
    }

    /**
     * @param \DateTime $date
     * @param int $sendBefore
     * @return bool
     */
    private function isTimeToSend($date, $sendBefore = 0)
    {
        $today = Carbon::today()->addDays($sendBefore)->format("Y-m-d");
        $sendingDate = Carbon::parse($date)->format("Y-m-d");
        if ($today == $sendingDate) {
            return true;
        } else {
            return false;
        }
    }
}
