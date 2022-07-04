<?php

namespace App\Domains\Users\DTOs\LoginDetails;

final class LoginDetailsUserAgent
{
    public ?string $userAgent;

    public ?string $device;

    public ?string $platform;

    public ?string $platformVersion;

    public ?string $browser;

    public ?string $browserVersion;
}
