<?php

use App\Domains\Admin\Enums\Translation\AdminNavigationGroupTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminResourcePropertyTranslationKey;
use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;
use App\Domains\Generic\Utils\LangUtils;
use App\Domains\Users\Admin\Components\Widgets\CustomersChartWidget;
use App\Domains\Users\Admin\Resources\UserResource;

return [
    CustomersChartWidget::class => [
        AdminWidgetPropertyTranslationKey::HEADING->name => 'Customers Per Month',
    ],
    UserResource::class => [
        AdminResourcePropertyTranslationKey::LABEL->name => 'Customer',
        AdminResourcePropertyTranslationKey::PLURAL_LABEL->name => 'Customers',
        AdminResourcePropertyTranslationKey::NAVIGATION_LABEL->name => 'Customers',
        AdminResourcePropertyTranslationKey::NAVIGATION_GROUP->name => LangUtils::translateEnum(AdminNavigationGroupTranslationKey::GENERIC),
    ],
];
