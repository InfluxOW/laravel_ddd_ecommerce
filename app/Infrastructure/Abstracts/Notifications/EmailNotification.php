<?php

namespace App\Infrastructure\Abstracts\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

abstract class EmailNotification extends Notification
{
    public function via(): array
    {
        return ['mail'];
    }

    abstract public function toMail(mixed $notifiable): MailMessage;
}
