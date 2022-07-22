<?php

use App\Domains\Feedback\Enums\Translation\FeedbackSettingsTranslationKey;
use App\Domains\Feedback\Enums\Translation\FeedbackTranslationKey;

return [
    FeedbackTranslationKey::class => [
        FeedbackTranslationKey::ID->name => 'ID',
        FeedbackTranslationKey::USERNAME->name => 'Username',
        FeedbackTranslationKey::EMAIL->name => 'Email',
        FeedbackTranslationKey::PHONE->name => 'Phone',
        FeedbackTranslationKey::USER->name => 'User',
        FeedbackTranslationKey::TEXT->name => 'Text',
        FeedbackTranslationKey::IS_REVIEWED->name => 'Is Reviewed',
        FeedbackTranslationKey::CREATED_AT->name => 'Created At',
    ],
    FeedbackSettingsTranslationKey::class => [
        FeedbackSettingsTranslationKey::FEEDBACK_LIMIT_PER_HOUR->name => 'Feedback Limit Per Hour',
    ],
];
