<?php

use App\Domain\Users\Enums\Translation\UserResourceTranslationKey;

return [
    UserResourceTranslationKey::class => [
        UserResourceTranslationKey::NAME->value => 'Name',
        UserResourceTranslationKey::EMAIL->value => 'Email',
        UserResourceTranslationKey::PHONE->value => 'Phone',
        UserResourceTranslationKey::PASSWORD->value => 'Password',
        UserResourceTranslationKey::EMAIL_VERIFIED_AT->value => 'Email Verified At',
    ],
];