<?php

namespace App\Domains\Catalog\Enums\Translation;

enum ProductAttributeResourceTranslationKey: string
{
    case TITLE = 'title';
    case SLUG = 'slug';
    case VALUES_TYPE = 'values_type';
}
