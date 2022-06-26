<?php

namespace App\Infrastructure\Abstracts\Database;

use App\Application\Classes\ApplicationState;
use Illuminate\Database\Seeder as BaseSeeder;

abstract class Seeder extends BaseSeeder
{
    public function __invoke(array $parameters = [])
    {
        ApplicationState::$isRunningSeeders = true;

        $result = parent::__invoke($parameters);

        ApplicationState::$isRunningSeeders = false;

        return $result;
    }
}
