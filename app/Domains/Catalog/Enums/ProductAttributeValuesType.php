<?php

namespace App\Domains\Catalog\Enums;

use App\Components\Generic\Enums\Response\ResponseValueType;

enum ProductAttributeValuesType: int
{
    case INTEGER = 0;
    case FLOAT = 1;
    case STRING = 2;
    case BOOLEAN = 3;

    public function readableType(): ResponseValueType
    {
        return match ($this) {
            self::INTEGER => ResponseValueType::INTEGER,
            self::FLOAT => ResponseValueType::FLOAT,
            self::STRING => ResponseValueType::STRING,
            self::BOOLEAN => ResponseValueType::BOOLEAN,
        };
    }
}
