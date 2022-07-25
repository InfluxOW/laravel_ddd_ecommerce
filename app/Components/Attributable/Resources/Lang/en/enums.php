<?php

use App\Components\Attributable\Enums\AttributeValuesType;
use App\Components\Attributable\Enums\Translation\AttributeTranslationKey;
use App\Components\Attributable\Enums\Translation\AttributeValueTranslationKey;

return [
    AttributeValueTranslationKey::class => [
        AttributeValueTranslationKey::ATTRIBUTE_TITLE->name => 'Attribute',
        AttributeValueTranslationKey::ATTRIBUTE->name => 'Attribute',
        AttributeValueTranslationKey::READABLE_VALUE->name => 'Value',
        AttributeValueTranslationKey::VALUE_STRING->name => 'Value',
        AttributeValueTranslationKey::VALUE_BOOLEAN->name => 'Value',
        AttributeValueTranslationKey::VALUE_FLOAT->name => 'Value',
        AttributeValueTranslationKey::VALUE_INTEGER->name => 'Value',
    ],
    AttributeTranslationKey::class => [
        AttributeTranslationKey::TITLE->name => 'Title',
        AttributeTranslationKey::SLUG->name => 'Slug',
        AttributeTranslationKey::VALUES_TYPE->name => 'Type Of Values',
    ],
    AttributeValuesType::class => [
        AttributeValuesType::STRING->name => 'String',
        AttributeValuesType::BOOLEAN->name => 'Boolean',
        AttributeValuesType::FLOAT->name => 'Float',
        AttributeValuesType::INTEGER->name => 'Integer',
    ],
];
