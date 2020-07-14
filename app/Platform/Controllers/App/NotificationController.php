<?php

namespace Platform\Controllers\App;

use Illuminate\Http\Request;

class NotificationController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $user = auth()->user();
        // foreach ( $user->unreadNotifications as $notification) {
        //     return $notification->data['notification'];
        $user->unreadNotifications->transform(function ($notification) {
            $notification->date = $notification->created_at->diffForHumans();
            // }
            return $notification;
        });
        return $user->unreadNotifications;
    }
}
