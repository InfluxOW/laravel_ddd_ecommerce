<?php

namespace App\Domains\Feedback\Enums\Translation;

enum FeedbackResourceTranslationKey: string
{
    case USERNAME = 'username';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case USER = 'user';
    case TEXT = 'text';
    CASE IS_REVIEWED = 'is_reviewed';
    CASE CREATED_AT = 'created_at';
}
