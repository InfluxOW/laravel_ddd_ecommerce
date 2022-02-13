<?php

namespace App\Infrastructure\Abstracts\Notifications;

use App\Domains\Generic\Enums\QueueName;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification as BaseNotification;

abstract class Notification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue(QueueName::NOTIFICATIONS->value);
    }
}
