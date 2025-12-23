<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderStatusNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
  public function handle(OrderStatusChanged $event)
{
    $notificationService = app(\App\Services\NotificationService::class);
    
    $notificationService->send(
        $event->order->user,
        new \App\Notifications\OrderStatusChangedNotification($event->order)
    );
}
}
