<?php

namespace App\Domains\Generic\Enums;

enum QueueName: string
{
    case DEFAULT = 'default';
    case RESIZER = 'resizer';
    case NOTIFICATIONS = 'notifications';
}
