<?php

namespace App\Components\LoginHistoryable\Enums\Translation;

enum LoginHistoryTranslationKey: string
{
    case IP = 'ip';
    case USER_AGENT = 'user_agent';
    case DEVICE = 'device';
    case PLATFORM = 'platform';
    case PLATFORM_VERSION = 'platform_version';
    case BROWSER = 'browser';
    case BROWSER_VERSION = 'browser_version';
    case REGION_CODE = 'region_code';
    case REGION_NAME = 'region_name';
    case COUNTRY_CODE = 'country_code';
    case COUNTRY_NAME = 'country_name';
    case CITY = 'city';
    case LOCATION = 'location';
    case ZIP = 'zip';
    case TIME = 'created_at';
}
