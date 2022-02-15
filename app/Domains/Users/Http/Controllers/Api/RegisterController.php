<?php

namespace App\Domains\Users\Http\Controllers\Api;

use App\Domains\Users\Events\Registered;
use App\Domains\Users\Http\Requests\RegisterRequest;
use App\Domains\Users\Models\User;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        /* @phpstan-ignore-next-line */
        $user = User::query()->create($request->safe()->merge(['password' => bcrypt($request->password)])->toArray());

        Registered::dispatch($user);

        return $this->respondWithMessage("We sent a confirmation email to {$user->email}. Please, follow the instructions to complete your registration.", Response::HTTP_CREATED);
    }
}
