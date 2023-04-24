<?php

namespace App\Domains\Users\Actions;

use App\Domains\Users\Events\Registered;
use App\Domains\Users\Http\Requests\RegisterRequest;
use App\Domains\Users\Models\User;
use Illuminate\Support\ValidatedInput;

final class RegisterUserAction
{
    public function execute(RegisterRequest $request): User
    {
        /** @var ValidatedInput $input */
        $input = $request->safe();
        /** @var string $password */
        $password = $request->getPassword();
        $user = User::query()->create($input->merge(['password' => bcrypt($password)])->toArray());

        Registered::dispatch($user);

        return $user;
    }
}
