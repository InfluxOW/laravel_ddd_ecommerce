<?php

namespace App\Domain\Products\Enums;

enum ProductAllowedSort: string
{
    case TITLE = 'title';
    case CREATED_AT = 'created_at';
    case PRICE = 'price';

    public function descendingValue(): string
    {
        return "-{$this->value}";
    }
}
