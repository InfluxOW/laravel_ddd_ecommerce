<?php

namespace App\Domain\Catalog\Enums\Translation;

enum ProductCategoryResourceTranslationKey: string
{
    case MAIN = 'main';
    case STATISTICS = 'statistics';

    case PATH = 'path';
    case TITLE = 'title';
    case SLUG = 'slug';
    case DESCRIPTION = 'description';
    case PARENT_ID = 'parent_id';
    case PARENT_TITLE = 'parent.title';
    case LEFT = 'left';

    case DEPTH = 'depth';
}
