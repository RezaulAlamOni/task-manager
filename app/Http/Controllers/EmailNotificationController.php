<?php

namespace App\Http\Controllers;

use App\EmailAndNotification;
use App\User;
use Illuminate\Http\Request;

class EmailNotificationController extends Controller
{
    public function getAllNotifications()
    {
        $notifications = EmailAndNotification::with('children')->whereNull('parent_id')->get();
        return response()->json($notifications, 200);
    }

    public function usersNotifications()
    {
//        $notifications = EmailAndNotification::with('users')->get();
        $notifications = User::select()->with('notifications:id')->find(1)->notifications;
        return $notifications;
    }
}
