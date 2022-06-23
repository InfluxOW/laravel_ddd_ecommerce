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
use Jenssegers\Agent\Agent;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Torann\GeoIP\Facades\GeoIP as GeoIPFacade;
use Torann\GeoIP\Location;

final class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        try {
            /* @phpstan-ignore-next-line */
            $user = $this->retrieveUserByCredentials($request->safe(['email', 'password']));
            $this->checkEmailVerification($user);

            // TODO: Remove HTTP request from transaction
            $accessToken = DB::transaction(function () use ($user, $request): NewAccessToken {
                $accessToken = $this->createAccessToken($user);

                $this->processUserDevice($user, $request);

                return $accessToken;
            });
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

    private function processUserDevice(User $user, LoginRequest $request): void
    {
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());
        $agent->setHttpHeaders($request->headers->all());

        $location = GeoIPFacade::getLocation($request->getIp());
        $loginDetails = $this->getLoginDetails($agent, $location);

        $user->loginHistory()->create($loginDetails);
    }

    private function getLoginDetails(Agent $agent, Location $location): array
    {
        return array_merge($this->getAgentInfo($agent), $this->getLocationInfo($location));
    }

    private function getAgentInfo(Agent $agent): array
    {
        $getStringOrNull = static fn (mixed $param): ?string => is_string($param) ? $param : null;

        $platform = $getStringOrNull($agent->platform());
        $browser = $getStringOrNull($agent->browser());
        $device = $getStringOrNull($agent->device());

        return [
            'user_agent' => $agent->getUserAgent(),
            'device' => $device,
            'platform' => $platform,
            'platform_version' => ($platform === null) ? null : $getStringOrNull($agent->version($platform)),
            'browser' => $browser,
            'browser_version' => ($browser === null) ? null : $getStringOrNull($agent->version($browser)),
        ];
    }

    private function getLocationInfo(Location $location): array
    {
        if ($location->default) {
            return [];
        }

        return [
            'ip' => $location->ip,
            'region_code' => $location->state,
            'region_name' => $location->state_name,
            'country_code' => $location->iso_code,
            'country_name' => $location->country,
            'city' => $location->city,
            'latitude' => $location->lat,
            'longitude' => $location->lon,
            'zip' => $location->postal_code,
        ];
    }
}
