<?php

namespace App\Components\Generic\Utils;

use App\Components\Generic\Enums\EnvironmentVariable;

class AppUtils
{
    public static function runningSeeders(): bool
    {
        $runningSeeders = getenv(EnvironmentVariable::RUNNING_SEEDERS->name);

        return is_string($runningSeeders) && $runningSeeders;
    }
}
