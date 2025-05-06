<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TripApproved extends Notification
{
    use Queueable;

    private $trip;

    public function __construct($trip)
    {
        $this->trip = $trip;
    }

    public function via($notifiable)
    {
        return ['database']; // تخزين الإشعار في قاعدة البيانات
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'تم قبول طلب رحلتك: ' . $this->trip->name,
        ];
    }
}
