<?php

namespace App\Domain\Generic\Query\Models\Filter;

use App\Domain\Generic\Query\Enums\QueryFilterType;

class InputFilter extends Filter
{
    public static QueryFilterType $type = QueryFilterType::INPUT;

    public function ofValues(mixed ...$values): static
    {
        return clone($this);
    }
}
