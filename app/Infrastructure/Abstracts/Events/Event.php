<?php

namespace App\Infrastructure\Abstracts\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use Dispatchable;
    use SerializesModels;
}
