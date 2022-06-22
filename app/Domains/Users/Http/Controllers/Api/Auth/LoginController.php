<?php

namespace App\Domains\Users\Http\Controllers\Api\Auth;

use App\Domains\Generic\Exceptions\HttpException;
use App\Domains\Users\Events\EmailVerificationFailed;
use App\Domains\Users\Events\Login;
use App\Domains\Users\Http\Requests\LoginRequest;
use App\Domains\Users\Models\User;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        try {
            /* @phpstan-ignore-next-line */
            $user = $this->retrieveUserByCredentials($request->safe(['email', 'password']));
            $this->checkEmailVerification($user);
            $accessToken = $this->createAccessToken($user);
        } catch (HttpException $e) {
            return $this->respondWithMessage($e->getMessage(), $e->getCode());
        } catch (Throwable) {
            return $this->respondWithMessage('Sorry, something went wrong. Please, try again later!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Login::dispatch($user);

        return $this->respondWithCustomData([
            'access_token' => $accessToken->plainTextToken,
        ]);
    }

    private function retrieveUserByCredentials(array $credentials): User
    {
        if (Auth::validate($credentials)) {
            /** @var User $user */
            $user = Auth::retrieveUserByCredentials($credentials);

            return $user;
        }

        throw new HttpException('Sorry, wrong email address or password. Please, try again!', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function checkEmailVerification(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        EmailVerificationFailed::dispatch($user);

        throw new HttpException("We sent a confirmation email to {$user->email}. Please, follow the instructions to complete your registration.", Response::HTTP_FORBIDDEN);
    }

    private function createAccessToken(User $user): NewAccessToken
    {
        return DB::transaction(static function () use ($user): NewAccessToken {
            $user->tokens()->delete();

            return $user->createToken('access_token');
        });
    }
}
