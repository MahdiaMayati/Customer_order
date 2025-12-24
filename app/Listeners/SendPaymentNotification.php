<?php

namespace App\Listeners;

use App\Events\PaymentProcessed;
use App\Notifications\PaymentSuccessNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentNotification
{
    public function __construct()
    {
        //
    }
  public function handle(PaymentProcessed $event)
{
   $event->user->notify(new \App\Notifications\PaymentSuccessNotification($event->amount));

}
}
