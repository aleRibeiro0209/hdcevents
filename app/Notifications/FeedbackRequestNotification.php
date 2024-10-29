<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeedbackRequestNotification extends Notification
{

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nos envie seu feedback sobre ' . $this->event->title)
                    ->greeting('Olá, ' . $notifiable->name . '!')
                    ->line('O evento "' . $this->event->title . '" que você participou já acabou.')
                    ->action('Deixe seu feedback', url('/events/' . $this->event->id . '/feedback'))
                    ->line('Agradecemos sua participação e esperamos sua avaliação!')
                    ->salutation('Atenciosamente, HDC Events');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
