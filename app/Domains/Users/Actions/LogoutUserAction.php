<?php

namespace App\Domains\Users\Actions;

use App\Domains\Users\Events\Logout;
use App\Domains\Users\Models\User;
use Illuminate\Http\Request;

final class LogoutUserAction
{
    public function execute(Request $request): void
    {
        /** @var User $user */
        $user = $request->user();

        $user->tokens()->delete();

        Logout::dispatch($user);
    }
}
