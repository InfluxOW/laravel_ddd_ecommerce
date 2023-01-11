<?php

namespace App\Domains\Users;

use App\Domains\Users\Enums\Translation\UserDatasetTranslationKey;
use App\Domains\Users\Enums\Translation\UserTranslationKey;

return [
    UserTranslationKey::class => [
        UserTranslationKey::ID->name => 'ID',
        UserTranslationKey::NAME->name => 'Name',
        UserTranslationKey::EMAIL->name => 'Email',
        UserTranslationKey::PHONE->name => 'Phone',
        UserTranslationKey::PASSWORD->name => 'Password',
        UserTranslationKey::HAS_VERIFIED_EMAIL->name => 'Has Verified Email',
        UserTranslationKey::EMAIL_VERIFIED_AT->name => 'Email Verified At',
        UserTranslationKey::LAST_LOGGED_IN_AT->name => 'Last Logged In At',
        UserTranslationKey::CREATED_AT->name => 'Created At',
        UserTranslationKey::UPDATED_AT->name => 'Updated At',
    ],
    UserDatasetTranslationKey::class => [
        UserDatasetTranslationKey::CUSTOMERS->name => 'Customers',
    ],
];
