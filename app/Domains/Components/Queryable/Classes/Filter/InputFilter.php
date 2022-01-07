<?php

namespace App\Domains\Components\Queryable\Classes\Filter;

use App\Domains\Components\Queryable\Enums\QueryFilterType;

class InputFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::INPUT;

    public function ofValues(mixed ...$values): static
    {
        return clone($this);
    }
}
