<?php

namespace App\Services\Notification;

use App\Models\User;
use Illuminate\Http\Request;

class NotificationQueryServices
{
    public function findNotificationUser(Request $request)
    {
        $users = User::find($request->user_id);
        return $users->notifications;
    }

    public function countNotificationUser($id)
    {
        $users = User::find($id);
        return $users->unreadNotifications->count();
    }
}
