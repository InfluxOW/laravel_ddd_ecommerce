<?php

namespace App\Components\Queryable\Abstracts;

use App\Components\Queryable\Abstracts\Filter\AllowedFilterEnum;
use App\Components\Queryable\Classes\Filter\Filter;
use UnitEnum;

interface FilterBuilder
{
    public function build(UnitEnum & AllowedFilterEnum $filter): Filter;
}
