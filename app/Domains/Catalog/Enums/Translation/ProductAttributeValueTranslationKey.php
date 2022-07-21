<?php

namespace App\Domains\Catalog\Enums\Translation;

enum ProductAttributeValueTranslationKey: string
{
    case ATTRIBUTE_TITLE = 'attribute.title';
    case READABLE_VALUE = 'readable_value';
    case ATTRIBUTE = 'attribute_id';

    case VALUE_STRING = 'value_string';
    case VALUE_BOOLEAN = 'value_boolean';
    case VALUE_INTEGER = 'value_integer';
    case VALUE_FLOAT = 'value_float';
}
