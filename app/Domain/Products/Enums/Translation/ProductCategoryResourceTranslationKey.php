<?php

namespace App\Domain\Products\Enums\Translation;

enum ProductCategoryResourceTranslationKey: string
{
    case MAIN = 'main';
    case STATISTICS = 'statistics';

    case PATH = 'path';
    case TITLE = 'title';
    case SLUG = 'slug';
    case PARENT_ID = 'parent_id';
    case PARENT_TITLE = 'parent_title';
    case LEFT = 'left';

    case DEPTH = 'depth';
}
