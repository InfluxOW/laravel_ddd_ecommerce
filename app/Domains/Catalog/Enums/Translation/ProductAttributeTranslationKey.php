<?php

namespace App\Domains\Catalog\Enums\Translation;

enum ProductAttributeTranslationKey: string
{
    case TITLE = 'title';
    case SLUG = 'slug';
    case VALUES_TYPE = 'values_type';
}
