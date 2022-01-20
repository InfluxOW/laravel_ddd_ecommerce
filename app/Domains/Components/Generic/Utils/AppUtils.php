<?php

namespace App\Domains\Components\Generic\Utils;

use App\Domains\Components\Generic\Enums\EnvironmentVariable;

class AppUtils
{
    public static function runningSeeders(): bool
    {
        $runningSeeders = getenv(EnvironmentVariable::RUNNING_SEEDERS->name);

        return is_string($runningSeeders) && $runningSeeders;
    }
}
