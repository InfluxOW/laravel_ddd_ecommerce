<?php

namespace App\Components\Purchasable;

use App\Components\Purchasable\Admin\RelationManagers\PricesRelationManager;
use App\Domains\Admin\Enums\Translation\AdminRelationPropertyTranslationKey;

return [
    PricesRelationManager::class => [
        AdminRelationPropertyTranslationKey::TITLE->name => 'Prices',
        AdminRelationPropertyTranslationKey::LABEL->name => 'price',
        AdminRelationPropertyTranslationKey::PLURAL_LABEL->name => 'prices',
    ],
];
