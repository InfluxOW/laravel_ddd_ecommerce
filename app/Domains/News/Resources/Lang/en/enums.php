<?php

use App\Domains\News\Enums\Translation\ArticleTranslationKey;

return [
    ArticleTranslationKey::class => [
        ArticleTranslationKey::ID->name => 'ID',
        ArticleTranslationKey::TITLE->name => 'Title',
        ArticleTranslationKey::SLUG->name => 'Slug',
        ArticleTranslationKey::DESCRIPTION->name => 'Description',
        ArticleTranslationKey::BODY->name => 'Body',
        ArticleTranslationKey::IMAGES->name => 'Images',
        ArticleTranslationKey::PUBLISHED_AT->name => 'Published At',
        ArticleTranslationKey::IS_PUBLISHED->name => 'Published',
        ArticleTranslationKey::CREATED_AT->name => 'Created At',
        ArticleTranslationKey::UPDATED_AT->name => 'Updated At',
    ],
];
