<?php

namespace App\Domain\Products\Enums;

use App\Domain\Generic\Query\Enums\AttributeValuesType;

enum ProductAttributeValuesType: int
{
    case INTEGER = 0;
    case FLOAT = 1;
    case STRING = 2;
    case BOOLEAN = 3;

    public function readableType(): AttributeValuesType
    {
        return match($this) {
            self::INTEGER => AttributeValuesType::INTEGER,
            self::FLOAT => AttributeValuesType::FLOAT,
            self::STRING => AttributeValuesType::STRING,
            self::BOOLEAN => AttributeValuesType::BOOLEAN,
        };
    }
}
