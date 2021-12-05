<?php

namespace App\Domain\Generic\Query\Models\Filter;

use App\Domain\Generic\Query\Enums\FrontendFilterType;

class InputFilter extends Filter
{
    public static FrontendFilterType $type = FrontendFilterType::INPUT;

    public function ofValues(mixed ...$values): static
    {
        return clone ($this);
    }
}
