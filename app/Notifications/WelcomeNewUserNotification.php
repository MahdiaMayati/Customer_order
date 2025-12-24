<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNewUserNotification extends Notification
{
    use Queueable;
    public function __construct()
    {
        //
    }
    public function onChannels()
    {
        return ['database'];
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
        'title' => 'أهلاً بك في نظامنا',
        'message' => 'شكراً لتسجيلك، نتمنى لك تجربة سعيدة.',
        'type' => 'welcome_message'
        ];
    }
}
