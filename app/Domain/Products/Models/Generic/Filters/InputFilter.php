<?php

namespace App\Domain\Products\Models\Generic\Filters;

use App\Domain\Products\Enums\Filters\FrontendFilterType;
use App\Domain\Products\Enums\Filters\ProductAllowedFilter;

class InputFilter extends Filter
{
    public static FrontendFilterType $type = FrontendFilterType::INPUT;

    public function __construct(ProductAllowedFilter $filter)
    {
        parent::__construct($filter);
    }

    public function ofValues(mixed ...$values): static
    {
        return clone ($this);
    }
}
