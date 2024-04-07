<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReceptionistNotification extends Notification
{
    use Queueable;

    private $guest_numero_ID;
    private $receptionist_numero_ID;
    private $title;
    private $message;
    public function __construct($guest_numero_ID,$receptionist_numero_ID,$title, $message)
    {
        $this->guest_numero_ID= $guest_numero_ID;
        $this->receptionist_numero_ID= $receptionist_numero_ID;
        $this->title = $title;
        $this->message=$message;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'guest_numero_ID'=>$this->guest_numero_ID,
            'admin_numero_ID'=>$this->receptionist_numero_ID,
            'title' => $this->title,
            'message' => $this->message
        ];
    }
}
