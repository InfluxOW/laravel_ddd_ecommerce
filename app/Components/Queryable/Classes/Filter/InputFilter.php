<?php

namespace App\Components\Queryable\Classes\Filter;

use App\Components\Queryable\Enums\QueryFilterType;

class InputFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::INPUT;

    public function ofValues(mixed ...$values): static
    {
        return clone($this);
    }
}
