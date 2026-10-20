<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification
{
    use Queueable;

    public function __construct(public ContactMessage $contactMessage) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = $this->contactMessage;

        return (new MailMessage)
            ->subject("Nuevo mensaje de {$message->name} desde tu web")
            ->replyTo($message->email, $message->name)
            ->line("**Nombre:** {$message->name}")
            ->line("**Email:** {$message->email}")
            ->line('**Teléfono:** '.($message->phone ?: '—'))
            ->line('**Tipo de proyecto:** '.($message->budget ?: '—'))
            ->line($message->message)
            ->action('Ver en el panel', url('/admin/contact-messages'));
    }
}
