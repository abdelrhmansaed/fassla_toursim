<?php

namespace App\Notifications;

use App\Models\TripRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TripApprovedPendingPayment extends Notification
{
    use Queueable;

    private $tripRequest;

    public function __construct(TripRequest $tripRequest)
    {
        $this->tripRequest = $tripRequest;
    }

    public function via($notifiable)
    {
        return ['database']; // تخزين الإشعار في قاعدة البيانات
    }

    public function toDatabase($notifiable)
    {
        $url = url('/trips/provider-approved');

        return [
            'title' => '🚀 تم قبول رحلة جديدة وهي بانتظار الدفع!',
            'message' => 'قام مزود الخدمة بالموافقة على رحلة: ' . $this->tripRequest->trip->name,
            'trip_id' => $this->tripRequest->trip->id,
            'trip_date' => $this->tripRequest->trip->date,
            'requested_by' => auth()->guard('provider')->user()->name,
            'url' =>$url, // الرابط الذي سينتقل إليه الإدمن عند الضغط على الإشعار
        ];
    }
}
