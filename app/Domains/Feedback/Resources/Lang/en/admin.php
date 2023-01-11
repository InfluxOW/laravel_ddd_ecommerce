<?php

namespace App\Domains\Feedback;

use App\Domains\Admin\Enums\Translation\AdminNavigationGroupTranslationKey as BaseAdminNavigationGroupTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminPagePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Common\Utils\LangUtils;
use App\Domains\Feedback\Admin\Pages\ManageFeedbackSettings;
use App\Domains\Feedback\Admin\Resources\FeedbackResource;
use App\Domains\Feedback\Enums\Translation\AdminNavigationGroupTranslationKey;

return [
    FeedbackResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Feedback',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Feedback',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Feedback',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::FEEDBACK),
    ],
    ManageFeedbackSettings::class => [
        AdminPagePropertyTranslationKey::TITLE->name => 'Feedback Settings',
        AdminPagePropertyTranslationKey::NAVIGATION_LABEL->name => 'Feedback',
        AdminPagePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(BaseAdminNavigationGroupTranslationKey::SETTINGS),
    ],
];
