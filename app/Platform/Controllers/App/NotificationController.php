<?php

namespace Platform\Controllers\App;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
            // dd($notification->data['notification'],Str::contains($notification->data['notification'], 'Message from'));
            if (Str::contains($notification->data['notification'], 'Message from')) {
                $notification->icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell" width="25" height="40" viewBox="0 0 24 24" stroke-width="1" stroke="#e94c23" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z"/>
                    <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                    <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                  </svg>';
            } elseif (Str::contains($notification->data['notification'], 'Thanks for supporting')) {
                $notification->icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mood-happy" width="25" height="40" viewBox="0 0 24 24" stroke-width="1" stroke="#e94c23" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z"/>
                    <circle cx="12" cy="12" r="9" />
                    <line x1="9" y1="9" x2="9.01" y2="9" />
                    <line x1="15" y1="9" x2="15.01" y2="9" />
                    <path d="M8 13a4 4 0 1 0 8 0m0 0H8" />
                  </svg>';
            } elseif (Str::contains($notification->data['notification'], 'Your Piggy Bank account was created')) {
                $notification->icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" width="25" height="40" viewBox="0 0 24 24" stroke-width="1" stroke="#e94c23" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z"/>
                    <circle cx="12" cy="12" r="9" />
                    <path d="M9 12l2 2l4 -4" />
                  </svg>';
            } elseif (Str::contains($notification->data['notification'], 'Congrats! You successfully redeemed')) {
                $notification->icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-coin" width="25" height="40" viewBox="0 0 24 24" stroke-width="1" stroke="#e94c23" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z"/>
                  <circle cx="12" cy="12" r="9" />
                  <path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 0 0 0 4h2a2 2 0 0 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
                  <path d="M12 6v2m0 8v2" />
                </svg>';
            }
            $notification->date = $notification->created_at->diffForHumans();
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
