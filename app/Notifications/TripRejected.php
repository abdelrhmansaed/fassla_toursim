<?php

namespace App\Notifications;

use App\Models\TripRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TripRejected extends Notification
{
    use Queueable;

    private $tripRequest;

    public function __construct(TripRequest $tripRequest)
    {
        $this->tripRequest = $tripRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => '❌ تم رفض رحلتك!',
            'message' => 'للأسف، تم رفض رحلتك: ' . $this->tripRequest->trip->name,
            'trip_id' => $this->tripRequest->trip->id,
            'trip_date' => $this->tripRequest->trip->date,
            'requested_by' => $this->tripRequest->agent->name, // ✅ إضافة اسم المندوب
            'url' => route('agent.rejectedTrips'), // رابط صفحة الطلبات للمندوب
        ];
    }
}
