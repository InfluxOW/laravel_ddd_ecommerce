<?php

namespace App\Domains\Catalog\Enums\Translation;

enum ProductTranslationKey: string
{
    case ID = 'id';

    case TITLE = 'title';
    case SLUG = 'slug';
    case DESCRIPTION = 'description';

    case CATEGORIES = 'categories';
    case IMAGES = 'images';

    case PRICES_STRING = 'prices_string';
    case CATEGORIES_STRING = 'categories_string';
    case ATTRIBUTE_VALUES_STRING = 'attribute_values_string';

    case IS_VISIBLE = 'is_visible';
    case IS_DISPLAYABLE = 'is_displayable';

    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';
}
