<?php

use App\Domains\Users\Enums\Translation\LoginHistoryTranslationKey;
use App\Domains\Users\Enums\Translation\UserTranslationKey;

return [
    UserTranslationKey::class => [
        UserTranslationKey::NAME->name => 'Name',
        UserTranslationKey::EMAIL->name => 'Email',
        UserTranslationKey::PHONE->name => 'Phone',
        UserTranslationKey::PASSWORD->name => 'Password',
        UserTranslationKey::EMAIL_VERIFIED_AT->name => 'Email Verified At',
    ],
    LoginHistoryTranslationKey::class => [
        LoginHistoryTranslationKey::IP->name => 'Ip',
        LoginHistoryTranslationKey::USER_AGENT->name => 'User Agent',
        LoginHistoryTranslationKey::DEVICE->name => 'Device',
        LoginHistoryTranslationKey::PLATFORM->name => 'Platform',
        LoginHistoryTranslationKey::PLATFORM_VERSION->name => 'Platform Version',
        LoginHistoryTranslationKey::BROWSER->name => 'Browser',
        LoginHistoryTranslationKey::BROWSER_VERSION->name => 'Browser Version',
        LoginHistoryTranslationKey::REGION_CODE->name => 'Region Code',
        LoginHistoryTranslationKey::REGION_NAME->name => 'Region',
        LoginHistoryTranslationKey::COUNTRY_CODE->name => 'Country Code',
        LoginHistoryTranslationKey::COUNTRY_NAME->name => 'Country',
        LoginHistoryTranslationKey::CITY->name => 'City',
        LoginHistoryTranslationKey::LOCATION->name => 'Location',
        LoginHistoryTranslationKey::ZIP->name => 'ZIP',
        LoginHistoryTranslationKey::TIME->name => 'Time',
    ],
];
