<?php

use App\Domains\Users\Enums\Translation\LoginHistoryResourceTranslationKey;
use App\Domains\Users\Enums\Translation\UserResourceTranslationKey;

return [
    UserResourceTranslationKey::class => [
        UserResourceTranslationKey::NAME->name => 'Name',
        UserResourceTranslationKey::EMAIL->name => 'Email',
        UserResourceTranslationKey::PHONE->name => 'Phone',
        UserResourceTranslationKey::PASSWORD->name => 'Password',
        UserResourceTranslationKey::EMAIL_VERIFIED_AT->name => 'Email Verified At',
    ],
    LoginHistoryResourceTranslationKey::class => [
        LoginHistoryResourceTranslationKey::IP->name => 'Ip',
        LoginHistoryResourceTranslationKey::USER_AGENT->name => 'User Agent',
        LoginHistoryResourceTranslationKey::DEVICE->name => 'Device',
        LoginHistoryResourceTranslationKey::PLATFORM->name => 'Platform',
        LoginHistoryResourceTranslationKey::PLATFORM_VERSION->name => 'Platform Version',
        LoginHistoryResourceTranslationKey::BROWSER->name => 'Browser',
        LoginHistoryResourceTranslationKey::BROWSER_VERSION->name => 'Browser Version',
        LoginHistoryResourceTranslationKey::REGION_CODE->name => 'Region Code',
        LoginHistoryResourceTranslationKey::REGION_NAME->name => 'Region',
        LoginHistoryResourceTranslationKey::COUNTRY_CODE->name => 'Country Code',
        LoginHistoryResourceTranslationKey::COUNTRY_NAME->name => 'Country',
        LoginHistoryResourceTranslationKey::CITY->name => 'City',
        LoginHistoryResourceTranslationKey::LOCATION->name => 'Location',
        LoginHistoryResourceTranslationKey::ZIP->name => 'ZIP',
        LoginHistoryResourceTranslationKey::TIME->name => 'Time',
    ],
];
