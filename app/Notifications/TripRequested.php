<?php

namespace App\Notifications;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Bus\Queueable;

class TripRequested extends Notification
{


    use Queueable;
    private $trip;

    public function __construct(Trip $trip)
    {
         $this->trip =$trip ;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        // تحديد الرابط بناءً على نوع المستخدم
        $url = null;
        if ($notifiable instanceof \App\Models\Admin) {
            $url = route('requests'); // ✅ رابط الإدمن
        } elseif ($notifiable instanceof \App\Models\Provider) {
            $url = route('provider.requests'); // ✅ رابط البروفايدر
        }
        return [
            'title' => '🚀 طلب رحلة جديد',
            'message' => 'تم تقديم طلب جديد لرحلة: ' . $this->trip->name,
            'trip_id' => $this->trip->id,
            'trip_date' => $this->trip->date,
            'requested_by' => auth()->guard('agent')->user()->name, // اسم المندوب الذي طلب الرحلة
            'role' => get_class($notifiable), // معرفة من استقبل الإشعار (Admin أو Provider)ط
            'url' => $url,
        ];
    }
}
