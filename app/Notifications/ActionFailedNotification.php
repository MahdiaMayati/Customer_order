<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ActionFailedNotification extends Notification
{
    protected $errorDetails;
    public $customChannels = ['database'];

    public function __construct($errorDetails)
    {
        $this->errorDetails = $errorDetails;
    }

    public function onChannels(array $channels) {
        $this->customChannels = $channels;
    }

    public function via($notifiable)
    {
        return $this->customChannels ?? ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->error()
                    ->subject('تنبيه: فشل في تنفيذ عملية')
                    ->line('حدث خطأ أثناء معالجة طلبك:')
                    ->line($this->errorDetails)
                    ->action('مراجعة النظام', url('/dashboard'));
    }

    public function toArray($notifiable)
    {
        return [
            'error' => $this->errorDetails,
            'time' => now()->toDateTimeString(),
        ];
    }
}
