<?php

namespace App\Domains\Feedback\Enums\Translation;

enum FeedbackResourceTranslationKey: string
{
    case USERNAME = 'username';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case USER = 'user';
    case TEXT = 'text';
}
