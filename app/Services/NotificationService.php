<?php
namespace App\Services;

use Illuminate\Support\Facades\Notification;

class NotificationService
{

public function send($notifiable, $notification,?array $channels = null, $delay = null)
    {
        if (!config('notifications.enabled', true)) {
            return;
        }

        if ($channels && method_exists($notification, 'onChannels')) {  
            $notification->onChannels($channels);
        }

        if ($delay) {
            Notification::delay($delay)->send($notifiable, $notification);
        } else {
            Notification::send($notifiable, $notification);
        }
    }
}
