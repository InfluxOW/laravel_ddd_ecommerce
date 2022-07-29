<?php

use App\Domains\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Generic\Utils\LangUtils;
use App\Domains\News\Admin\Resources\ArticleResource;

return [
    ArticleResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Article',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Articles',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'News',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::NEWS),
    ],
];
