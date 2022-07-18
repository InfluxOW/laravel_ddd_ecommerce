<?php

namespace App\Domains\Users\Http\Controllers\Api\Auth;

use App\Domains\Generic\Exceptions\HttpException;
use App\Domains\Users\Events\EmailVerificationFailed;
use App\Domains\Users\Events\Login;
use App\Domains\Users\Http\Requests\LoginRequest;
use App\Domains\Users\Models\User;
use App\Domains\Users\Services\LoginDetailsService;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Component\HttpFoundation\Response;

final class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, LoginDetailsService $loginDetailsService): JsonResponse
    {
        /* @phpstan-ignore-next-line */
        $user = $this->retrieveUserByCredentials($request->safe(['email', 'password']));
        $this->checkEmailVerification($user);
        $loginDetails = $loginDetailsService->getLoginDetails($request);

        $accessToken = DB::transaction(function () use ($loginDetailsService, $user, $loginDetails): NewAccessToken {
            $loginDetailsService->updateUserLoginHistory($user, $loginDetails);

            return $this->receiveNewAccessToken($user);
        });

        Login::dispatch($user);

        return $this->respondWithCustomData([
            'access_token' => $accessToken->plainTextToken,
        ]);
    }

    private function retrieveUserByCredentials(array $credentials): User
    {
        if (Auth::validate($credentials)) {
            /**
             * @var User $user
             *
             * @phpstan-ignore-next-line
             */
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

    private function receiveNewAccessToken(User $user): NewAccessToken
    {
        $user->tokens()->delete();

        return $user->createToken('access_token');
    }
}
