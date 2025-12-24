<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\PaymentProcessed;
use App\Listeners\SendPaymentNotification;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\OrderStatusChanged::class => [
        \App\Listeners\SendOrderStatusNotification::class,
        ],
        \App\Events\PaymentProcessed::class => [
        \App\Listeners\SendPaymentNotification::class,
    ],
    ];

    public function boot(): void
    {
        //
    }
    
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
