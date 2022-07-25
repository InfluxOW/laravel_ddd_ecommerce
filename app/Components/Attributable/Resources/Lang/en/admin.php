<?php

use App\Components\Attributable\Admin\RelationManagers\AttributeValuesRelationManager;
use App\Components\Attributable\Admin\Resources\AttributeResource;
use App\Domains\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Generic\Utils\LangUtils;

return [
    AttributeResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Attribute',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Attributes',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Attributes',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::CATALOG),
    ],
    AttributeValuesRelationManager::class => [
        AdminRelationPropertyTranslationKey::TITLE->name => 'Attribute Values',
        AdminRelationPropertyTranslationKey::LABEL->name => 'attribute value',
        AdminRelationPropertyTranslationKey::PLURAL_LABEL->name => 'attribute values',
    ],
];
