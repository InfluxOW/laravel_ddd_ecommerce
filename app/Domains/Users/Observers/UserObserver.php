<?php

namespace App\Domains\Users\Observers;

use App\Domains\Users\Models\User;

class UserObserver
{
    public function deleting(User $user): void
    {
        $user->addresses()->delete();
    }
}
