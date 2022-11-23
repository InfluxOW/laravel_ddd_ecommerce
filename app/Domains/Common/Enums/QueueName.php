<?php

namespace App\Domains\Common\Enums;

enum QueueName: string
{
    case DEFAULT = 'default';
    case RESIZER = 'resizer';
    case NOTIFICATIONS = 'notifications';
}
