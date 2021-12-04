<?php

namespace App\Domain\Products\Models\Generic\Sorts;

use App\Domain\Products\Enums\Sorts\ProductAllowedSort;
use App\Domain\Utils\LangUtils;

class Sort
{
    protected function __construct(public string $query, public string $title)
    {
    }

    public static function createAsc(ProductAllowedSort $sort): self
    {
        return new self($sort->value, LangUtils::translateEnum($sort));
    }

    public static function createDesc(ProductAllowedSort $sort): self
    {
        return new self($sort->descendingValue(), LangUtils::translateEnum($sort, $sort->descendingValue()));
    }

    public function toArray(): array
    {
        return [
            'query' => $this->query,
            'title' => $this->title,
        ];
    }
}
