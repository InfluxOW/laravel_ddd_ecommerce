<?php

namespace App\Components\LoginHistoryable\DTOs\LoginDetails;

use MStaack\LaravelPostgis\Geometries\Point;

final class LoginDetailsLocation
{
    public ?string $ip;

    public ?string $regionCode;

    public ?string $regionName;

    public ?string $countryCode;

    public ?string $countryName;

    public ?string $city;

    public ?Point $coordinates;

    public ?string $zip;
}
