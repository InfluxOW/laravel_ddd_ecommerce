<?php

namespace App\Domain\Users\Http\Controllers\Api;

use App\Domain\Users\Http\Requests\LoginRequest;
use App\Domain\Users\Http\Resources\UserResource;
use App\Domain\Users\Models\User;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (! Auth::attempt(Arr::only($validated, ['email', 'password']), $validated['remember'])) {
            return $this->respondWithMessage('Sorry, wrong email address or password. Please, try again!', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        /** @var User $user */
        $user = Auth::user();

        try {
            $accessToken = DB::transaction(function () use ($user): string {
                $user->tokens()->delete();

                return $user->createToken('access_token')->plainTextToken;
            });
        } catch (Throwable) {
            return $this->respondWithMessage('Sorry, something went wrong. Please, try again later!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->respondWithCustomData([
            'user' => UserResource::make($user),
            'access_token' => $accessToken,
        ]);
    }
}
