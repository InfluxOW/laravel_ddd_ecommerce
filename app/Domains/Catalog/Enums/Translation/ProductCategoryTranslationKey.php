<?php

namespace App\Domains\Catalog\Enums\Translation;

enum ProductCategoryTranslationKey: string
{
    case ID = 'id';

    case MAIN = 'main';
    case STATISTICS = 'statistics';

    case PATH = 'path';
    case TITLE = 'title';
    case SLUG = 'slug';
    case DESCRIPTION = 'description';
    case PARENT_ID = 'parent_id';
    case PARENT_TITLE = 'parent.title';
    case LEFT = 'left';
    case IS_VISIBLE = 'is_visible';
    case IS_DISPLAYABLE = 'is_displayable';

    case PRODUCTS_COUNT = 'products_count';
    case OVERALL_PRODUCTS_COUNT = 'overall_products_count';

    case IMAGES = 'images';
    case PRODUCTS_STRING = 'products_string';

    case DEPTH = 'depth';

    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';
}
