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

    public function changeNotification($id, Request $request)
    {
        $notification = EmailAndNotification::with('users')->find($id);
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification Not Found!'], 200);
        } else {
            if (isset($request->value)) {
                $result = false;
                $user = User::findOrFail(auth()->id());
                if ($request->value) {
                    $result = $user->notifications()->attach($id);
                } else if (!$request->value) {
                    $result = $user->notifications()->detach($id);
                }
                return response()->json(['success' => true, 'message' => $result], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Incorrect Value'], 200);
            }
        }
    }

    public function getNotificationsByUser($user_id)
    {
        $notifications = EmailAndNotification::select('id', 'title')->withCount(['users as hasNotification' => function ($q) use ($user_id) {
            $q->where('id', $user_id);
        }])->whereNotNull('parent_id')->get();
        $data = [];
        foreach ($notifications as $notification) {
            $data[$notification->title] = $notification->hasNotification;
        }
        return $data;
    }
}
