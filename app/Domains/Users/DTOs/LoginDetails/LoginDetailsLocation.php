<?php

namespace App\Domains\Users\DTOs\LoginDetails;

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
