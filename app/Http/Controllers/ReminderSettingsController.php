<?php

namespace App\Http\Controllers;

use App\Mail\ReminderMail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReminderSettingsController extends Controller
{
    /**
     * Send Due Date Reminder Email
     *
     * @return string
     */
    public function sendDueDateEmail()
    {
        $users = User::whereHas('tasks', function ($q) {
            $q->whereDate('date', Carbon::today()->addDays(2)->format("Y-m-d"));
        })->whereHas('notifications', function ($q) {
            $q->where('unique_id', 'reminder_whenTaskIsDue');
        })->with(['tasks' => function ($q) {
            $q->whereDate('date', Carbon::today()->addDays(2)->format("Y-m-d"));
        }, 'notifications' => function ($qr) {
            $qr->where('unique_id', 'reminder_whenTaskIsDue');
        }])->get();

        foreach ($users as $user) {
            $data = [];
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            foreach ($user->tasks as $task) {
                $data['subject'] = "Reminder for: " . $task->title;
                $data['title'] = $task->title;
                $data['date'] = $task->date;
                Mail::to($data['email'])->send(new ReminderMail($data));
            }
        }
        return "Email Send Successfully";
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
