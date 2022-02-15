<?php

namespace App\Domains\Users\Events;

use App\Infrastructure\Abstracts\Events\Event;
use Illuminate\Contracts\Auth\Authenticatable;

final class Logout extends Event
{
    public function __construct(public Authenticatable $user)
    {
    }
}
