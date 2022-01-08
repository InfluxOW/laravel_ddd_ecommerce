<?php

use App\Domains\Admin\Admin\Components\Widgets\CustomersChartWidget;
use App\Domains\Admin\Enums\Translation\AdminWidgetPropertyTranslationKey;

return [
    CustomersChartWidget::class => [
        AdminWidgetPropertyTranslationKey::HEADING->name => 'Customers Per Month',
    ],
];
