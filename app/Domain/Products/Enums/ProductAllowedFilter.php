<?php

namespace App\Domain\Products\Enums;

enum ProductAllowedFilter: string
{
    case TITLE = 'title';
    case DESCRIPTION = 'description';
    case CATEGORY = 'category';
    case PRICE_BETWEEN = 'price_between';
    case ATTRIBUTE = 'attribute';

    public function frontendType(): FrontendFilterType
    {
        return match ($this) {
            self::TITLE, self::DESCRIPTION => FrontendFilterType::INPUT,
            self::CATEGORY, self::ATTRIBUTE => FrontendFilterType::MULTISELECT,
            self::PRICE_BETWEEN => FrontendFilterType::RANGE,
        };
    }
}
