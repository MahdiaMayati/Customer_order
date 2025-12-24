<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification
{
    use Queueable;
    protected $data;
    public function __construct($data)
    {
       $this->data = $data;
    }
    public function onChannels()
    {
    return ['database'];
    }

    public function via(object $notifiable): array
    {
        return $notifiable->preferred_channels ??['database','mail'];

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
           'message' => 'تم إنشاء مستخدم جديد: ' . $this->data['name'],
            'user_id'=>$this->data['id']
        ];
    }
}
