<?php

namespace App\Infrastructure\Abstracts\Database;

use App\Domains\Generic\Enums\EnvironmentVariable;
use Illuminate\Database\Seeder as BaseSeeder;

abstract class Seeder extends BaseSeeder
{
    public function __invoke(array $parameters = [])
    {
        putenv(sprintf('%s=1', EnvironmentVariable::RUNNING_SEEDERS->name));

        $result = parent::__invoke($parameters);

        putenv(sprintf('%s=0', EnvironmentVariable::RUNNING_SEEDERS->name));

        return $result;
    }
}