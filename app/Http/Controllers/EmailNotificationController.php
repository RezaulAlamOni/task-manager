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
        $notifications = EmailAndNotification::with('users')->whereNotNull('parent_id')->get();
        $data = [];
        foreach ($notifications as $info) {
            $da['title'] = $info['title'];
//            $da[$info['unique_id']] = (count($info['users']) > 0 ? 1 : 0);
            $da['user'] = (count($info['users']) > 0 ? true : false);
            $da['unique_id'] = $info['unique_id'];
            $data[] = $da;
            $da = [];
        }
        return $data;
    }
}
