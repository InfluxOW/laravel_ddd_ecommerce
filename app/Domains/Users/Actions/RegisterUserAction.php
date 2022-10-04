<?php

namespace App\Domains\Users\Actions;

use App\Domains\Users\Events\Registered;
use App\Domains\Users\Http\Requests\RegisterRequest;
use App\Domains\Users\Models\User;

final class RegisterUserAction
{
    public function execute(RegisterRequest $request): User
    {
        /* @phpstan-ignore-next-line */
        $user = User::query()->create($request->safe()->merge(['password' => bcrypt($request->password)])->toArray());

        Registered::dispatch($user);

        return $user;
    }
}
