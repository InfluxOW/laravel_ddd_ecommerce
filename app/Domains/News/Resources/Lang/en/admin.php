<?php

namespace App\Domains\News;

use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Common\Utils\LangUtils;
use App\Domains\News\Admin\Resources\ArticleResource;
use App\Domains\News\Enums\Translation\AdminNavigationGroupTranslationKey;

return [
    ArticleResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Article',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Articles',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'News',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::NEWS),
    ],
];
