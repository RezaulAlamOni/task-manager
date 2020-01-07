<?php

namespace App\Http\Controllers;

use App\EmailAndNotification;
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
        $notifications = EmailAndNotification::with('users')->get();
        return $notifications;
    }
}
