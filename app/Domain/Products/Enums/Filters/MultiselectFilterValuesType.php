<?php

namespace App\Domain\Products\Enums\Filters;

enum MultiselectFilterValuesType: string
{
    case PLAIN = 'plain';
    case NESTED = 'nested';
}
