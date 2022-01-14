<?php

namespace App\Domains\Catalog\Enums\Translation;

enum ProductResourceTranslationKey: string
{
    case TITLE = 'title';
    case SLUG = 'slug';
    case DESCRIPTION = 'description';
}
