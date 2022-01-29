<?php

namespace App\Domains\Users\Http\Controllers\Api;

use App\Domains\Users\Http\Requests\RegisterRequest;
use App\Domains\Users\Http\Resources\UserResource;
use App\Domains\Users\Models\User;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create(
            array_merge(
                $request->validated(),
                ['password' => bcrypt($request->password)]
            )
        );
        $accessToken = $user->createToken('access_token')->plainTextToken;

        return $this->respondWithCustomData([
            'user' => UserResource::make($user),
            'access_token' => $accessToken,
        ]);
    }
}
