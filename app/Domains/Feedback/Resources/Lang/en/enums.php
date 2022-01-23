<?php

use App\Domains\Feedback\Enums\Translation\FeedbackResourceTranslationKey;
use App\Domains\Feedback\Enums\Translation\FeedbackSettingsTranslationKey;

return [
    FeedbackResourceTranslationKey::class => [
        FeedbackResourceTranslationKey::USERNAME->name => 'Username',
        FeedbackResourceTranslationKey::EMAIL->name => 'Email',
        FeedbackResourceTranslationKey::PHONE->name => 'Phone',
        FeedbackResourceTranslationKey::USER->name => 'User',
        FeedbackResourceTranslationKey::TEXT->name => 'Text',
    ],
    FeedbackSettingsTranslationKey::class => [
        FeedbackSettingsTranslationKey::FEEDBACK_LIMIT_PER_HOUR->name => 'Feedback Limit Per Hour',
    ],
];
