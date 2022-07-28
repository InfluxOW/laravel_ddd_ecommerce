<?php

namespace App\Components\LoginHistoryable\DTOs\LoginDetails;

final class LoginDetailsUserAgent
{
    public ?string $userAgent;

    public ?string $device;

    public ?string $platform;

    public ?string $platformVersion;

    public ?string $browser;

    public ?string $browserVersion;
}
