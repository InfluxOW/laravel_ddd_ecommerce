<?php

namespace App\Domains\Users\Enums\Translation;

enum UserTranslationKey: string
{
    case NAME = 'name';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case PASSWORD = 'password';
    case EMAIL_VERIFIED_AT = 'email_verified_at';
}
