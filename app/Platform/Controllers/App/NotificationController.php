<?php

namespace Platform\Controllers\App;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NotificationController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $user = auth()->user();
        // return $user->notifications;
        // foreach ($user->notifications as $notification) {
        //     return $notification;
        // }
        // foreach ( $user->unreadNotifications as $notification) {
        //     return $notification->data['notification'];
        $notifications = auth()->user()->notifications()->take(10)->orderBy('created_at', 'DESC')->get();
        $notifications->transform(function ($notification) {
            $notification->date = $notification->created_at->diffForHumans();
            // }
            return $notification;
        });
        $notification_count = $user->unreadNotifications->count();
        // $notification = Arr::prepend($user->notifications->toArray(), $notification_count, 'notification_count');
        return response()->json(['notifications' =>  $notifications, 'notification_count' => $notification_count], 200);

        return $user->notifications;
    }

    public function update(Request $request, $id)
    {
        return $request->all();
    }

    public function store(Request $request)
    {
        // return $request->all();
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();
    }
}
