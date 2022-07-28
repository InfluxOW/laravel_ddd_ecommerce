<?php

namespace App\Components\LoginHistoryable\Services;

use App\Components\LoginHistoryable\DTOs\LoginDetails\LoginDetails;
use App\Components\LoginHistoryable\DTOs\LoginDetails\LoginDetailsLocation;
use App\Components\LoginHistoryable\DTOs\LoginDetails\LoginDetailsUserAgent;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use MStaack\LaravelPostgis\Geometries\Point;
use Torann\GeoIP\Facades\GeoIP as GeoIPFacade;
use Torann\GeoIP\Location;

final class LoginDetailsService
{
    public function getLoginDetails(Request $request): LoginDetails
    {
        $location = GeoIPFacade::getLocation($request->getIp());

        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());
        $agent->setHttpHeaders($request->headers->all());

        $loginDetails = new LoginDetails();
        $loginDetails->agent = $this->getLoginDetailsUserAgent($agent);
        $loginDetails->location = $this->getLoginDetailsLocation($location);

        return $loginDetails;
    }

    private function getLoginDetailsUserAgent(Agent $agent): LoginDetailsUserAgent
    {
        $getStringOrNull = static fn (mixed $param): ?string => is_string($param) ? $param : null;

        $loginDetailsUserAgent = new LoginDetailsUserAgent();
        $loginDetailsUserAgent->userAgent = $agent->getUserAgent();
        $loginDetailsUserAgent->device = $getStringOrNull($agent->device());
        $loginDetailsUserAgent->platform = $getStringOrNull($agent->platform());
        $loginDetailsUserAgent->platformVersion = ($loginDetailsUserAgent->platform === null) ? null : $getStringOrNull($agent->version($loginDetailsUserAgent->platform));
        $loginDetailsUserAgent->browser = $getStringOrNull($agent->browser());
        $loginDetailsUserAgent->browserVersion = ($loginDetailsUserAgent->browser === null) ? null : $getStringOrNull($agent->version($loginDetailsUserAgent->browser));

        return $loginDetailsUserAgent;
    }

    private function getLoginDetailsLocation(Location $location): ?LoginDetailsLocation
    {
        if ($location->default) {
            return null;
        }

        $loginDetailsLocation = new LoginDetailsLocation();
        $loginDetailsLocation->ip = $location->ip;
        $loginDetailsLocation->regionCode = $location->state;
        $loginDetailsLocation->regionName = $location->state_name;
        $loginDetailsLocation->countryCode = $location->iso_code;
        $loginDetailsLocation->countryName = $location->country;
        $loginDetailsLocation->city = $location->city;
        $loginDetailsLocation->coordinates = ($location->lat === null || $location->lon === null) ? null : new Point($location->lat, $location->lon);
        $loginDetailsLocation->zip = $location->postal_code;

        return $loginDetailsLocation;
    }

    public function updateLoginHistory(Authenticatable $authenticatable, LoginDetails $loginDetails): void
    {
        $authenticatable->loginHistory()->create($this->prepareLoginDetails($loginDetails));
    }

    private function prepareLoginDetails(LoginDetails $loginDetails): array
    {
        $userAgent = $loginDetails->agent;
        $location = $loginDetails->location;

        return [
            'user_agent' => $userAgent->userAgent,
            'device' => $userAgent->device,
            'platform' => $userAgent->platform,
            'platform_version' => $userAgent->platformVersion,
            'browser' => $userAgent->browser,
            'browser_version' => $userAgent->browserVersion,
            'ip' => $location?->ip,
            'region_code' => $location?->regionCode,
            'region_name' => $location?->regionName,
            'country_code' => $location?->countryCode,
            'country_name' => $location?->countryName,
            'city' => $location?->city,
            'location' => $location?->coordinates,
            'zip' => $location?->zip,
        ];
    }
}
