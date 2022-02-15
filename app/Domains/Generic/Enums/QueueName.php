<?php

namespace App\Domains\Generic\Enums;

enum QueueName: string
{
    case RESIZER = 'resizer';
    case NOTIFICATIONS = 'notifications';
}
