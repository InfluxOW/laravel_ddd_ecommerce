<?php

use App\Domain\Users\Enums\Translation\UserResourceTranslationKey;

return [
    UserResourceTranslationKey::class => [
        UserResourceTranslationKey::NAME->name => 'Name',
        UserResourceTranslationKey::EMAIL->name => 'Email',
        UserResourceTranslationKey::PHONE->name => 'Phone',
        UserResourceTranslationKey::PASSWORD->name => 'Password',
        UserResourceTranslationKey::EMAIL_VERIFIED_AT->name => 'Email Verified At',
    ],
];
