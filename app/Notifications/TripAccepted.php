<?php
namespace App\Notifications;

use App\Models\TripRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Trip;
use App\Models\User;

class TripAccepted extends Notification
{
    use Queueable;
    private $tripRequest;
    public function __construct(TripRequest $tripRequest)
    {
        $this->tripRequest = $tripRequest;
    }


    public function via($notifiable)
    {
        return ['database']; // استخدام الإشعارات داخل التطبيق
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => '✅ تم تأكيد رحلتك!',
            'message' => 'تمت الموافقة على رحلتك: ' . $this->tripRequest->trip->name,
            'trip_id' => $this->tripRequest->trip->id,
            'trip_date' => $this->tripRequest->trip->date,
            'requested_by' => $this->tripRequest->agent->name, // ✅ إضافة اسم المندوب
            'url' => route('agent.confirmedTrips'), // رابط صفحة الطلبات للمندوب

        ];
    }

}
