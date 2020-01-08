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
//        $notifications = EmailAndNotification::with(['children' => function($q){
//            $q->with('users:id,name')->count();
//        }])->whereNull('parent_id')->get();
//        ->where('user_id', 1)
//        $notifications = User::select()->with('notifications:id')->find(1)->notifications->pluck('id');
        $notifications = EmailAndNotification::with(['children' => function ($q) {
            $q->withCount(['users as user' => function ($q) {
                $q->where('id', auth()->id());
//            $q->where('id', 3);
            }]);
        }])->whereNull('parent_id')->get();
        return $notifications;
    }
}
