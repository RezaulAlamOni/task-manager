<?php

namespace App\Http\Controllers;

use App\EmailAndNotification;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailNotificationController extends Controller
{
    /**
     * Get All the Notifications with Parent Child Relationship.
     *
     * @return JsonResponse
     */
    public function getAllNotifications()
    {
        $notifications = EmailAndNotification::with('children')->whereNull('parent_id')->get();
        return response()->json($notifications, 200);
    }

    /**
     * Get the Logged In User's Notifications status.
     *
     * @return JsonResponse
     */
    public function usersNotifications()
    {
        $notifications = EmailAndNotification::with(['children' => function ($q) {
            $q->withCount(['users as user' => function ($q) {
                $q->where('id', auth()->id());
            }]);
        }])->whereNull('parent_id')->get();
        return response()->json($notifications, 200);
    }

    /**
     * Change the Notification status.
     *
     * @param integer $id
     * @param Request $request
     * @return JsonResponse
     */
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

    /**
     * Get The Notifications status by User.
     *
     * @param integer $user_id
     * @return JsonResponse
     */
    public function getNotificationsByUser($user_id)
    {
        $notifications = EmailAndNotification::select('id', 'title')->withCount(['users as hasNotification' => function ($q) use ($user_id) {
            $q->where('id', $user_id);
        }])->whereNotNull('parent_id')->get();
        if ($notifications) {
            $data = [];
            foreach ($notifications as $notification) {
                $data[$notification->title] = $notification->hasNotification;
            }
            return response()->json($data, 200);
        }
        return response()->json(['success' => false, 'message' => 'No Notifications Found!'], 200);
    }
}
