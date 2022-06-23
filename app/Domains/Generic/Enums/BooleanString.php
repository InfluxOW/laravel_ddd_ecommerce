<?php

namespace App\Domains\Generic\Enums;

enum BooleanString: string
{
    // _ added to avoid Laravel Pint conflicts
    case _FALSE = 'false';
    case _TRUE = 'true';
}
