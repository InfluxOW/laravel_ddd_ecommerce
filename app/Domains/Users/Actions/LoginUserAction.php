<?php

namespace App\Domains\Users\Actions;

use App\Components\LoginHistoryable\Services\LoginDetailsService;
use App\Domains\Generic\Exceptions\HttpException;
use App\Domains\Users\Events\EmailVerificationFailed;
use App\Domains\Users\Events\Login;
use App\Domains\Users\Http\Requests\LoginRequest;
use App\Domains\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class LoginUserAction
{
    public function __construct(private readonly LoginDetailsService $loginDetailsService)
    {
    }

    /**
     * @throws HttpException
     * @throws Throwable
     */
    public function execute(LoginRequest $request): NewAccessToken
    {
        /** @var array $credentials */
        $credentials = $request->safe(['email', 'password']);
        $user = $this->retrieveUserByCredentials($credentials);

        $this->checkEmailVerification($user);

        $loginDetails = $this->loginDetailsService->getLoginDetails($request);

        $accessToken = DB::transaction(function () use ($user, $loginDetails): NewAccessToken {
            $this->loginDetailsService->updateLoginHistory($user, $loginDetails);

            return $this->receiveNewAccessToken($user);
        });

        Login::dispatch($user);

        return $accessToken;
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
