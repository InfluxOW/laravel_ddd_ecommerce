<?php

namespace App\Domains\Users\Enums\Translation;

enum UserTranslationKey: string
{
    case ID = 'id';
    case NAME = 'name';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case PASSWORD = 'password';
    case EMAIL_VERIFIED_AT = 'email_verified_at';
    case LAST_LOGGED_IN_AT = 'last_logged_in_at';
    case HAS_VERIFIED_EMAIL = 'has_verified_email';
    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';
}
