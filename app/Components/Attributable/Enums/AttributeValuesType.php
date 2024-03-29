<?php

namespace App\Components\Attributable\Enums;

use App\Domains\Common\Enums\Response\ResponseValueType;

enum AttributeValuesType: int
{
    case INTEGER = 0;
    case FLOAT = 1;
    case STRING = 2;
    case BOOLEAN = 3;

    public function responseValueType(): ResponseValueType
    {
        return match ($this) {
            self::INTEGER => ResponseValueType::INTEGER,
            self::FLOAT => ResponseValueType::FLOAT,
            self::STRING => ResponseValueType::STRING,
            self::BOOLEAN => ResponseValueType::BOOLEAN,
        };
    }
}
