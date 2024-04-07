<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WellcomGuestNotification extends Notification
{
    use Queueable;

    private $guest_numero_ID;
    private $title_1;
    private $message_1;
    public function __construct($guest_numero_ID,$title_1, $message_1)
    {
        $this->guest_numero_ID= $guest_numero_ID;
        $this->title_1 = $title_1;
        $this->message_1=$message_1;
    }


    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'guest_numero_ID'=>$this->guest_numero_ID,
            'title' => $this->title_1,
            'message' => $this->message_1
        ];
    }
}
