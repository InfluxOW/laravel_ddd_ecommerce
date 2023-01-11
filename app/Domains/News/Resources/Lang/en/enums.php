<?php

namespace App\Domains\News;

use App\Domains\News\Enums\Query\Filter\ArticleAllowedFilter;
use App\Domains\News\Enums\Query\Sort\ArticleAllowedSort;
use App\Domains\News\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domains\News\Enums\Translation\ArticleTranslationKey;

return [
    AdminNavigationGroupTranslationKey::class => [
        AdminNavigationGroupTranslationKey::NEWS->name => 'News',
    ],
    ArticleAllowedFilter::class => [
        ArticleAllowedFilter::SEARCH->name => 'Search',
        ArticleAllowedFilter::PUBLISHED_BETWEEN->name => 'Published',
    ],
    ArticleAllowedSort::class => [
        ArticleAllowedSort::DEFAULT->name => 'Default',
        ArticleAllowedSort::TITLE->name => 'Title A-Z',
        ArticleAllowedSort::PUBLISHED_AT->name => 'Lately Published First',
        ArticleAllowedSort::TITLE_DESC->name => 'Title Z-A',
        ArticleAllowedSort::PUBLISHED_AT_DESC->name => 'Recently Published First',
    ],
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
