<?php

namespace App\Domain\Users\Enums\Translation;

enum UserResourceTranslationKey: string
{
    case NAME = 'name';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case PASSWORD = 'password';
    case EMAIL_VERIFIED_AT = 'email_verified_at';
}
