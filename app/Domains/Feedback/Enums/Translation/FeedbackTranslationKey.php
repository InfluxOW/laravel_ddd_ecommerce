<?php

namespace App\Domains\Feedback\Enums\Translation;

enum FeedbackTranslationKey: string
{
    case USERNAME = 'username';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case USER = 'user';
    case TEXT = 'text';
    case IS_REVIEWED = 'is_reviewed';
    case CREATED_AT = 'created_at';
}
