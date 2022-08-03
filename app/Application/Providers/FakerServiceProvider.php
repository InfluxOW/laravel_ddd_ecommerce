<?php

namespace App\Application\Providers;

use Bezhanov\Faker\Provider\Commerce;
use Faker\Generator;
use Faker\Provider\Fakenews;
use Illuminate\Support\ServiceProvider;

final class FakerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->extend(Generator::class, function (Generator $faker): Generator {
            $faker->addProvider(new Commerce($faker));
            $faker->addProvider(new Fakenews($faker));

            return $faker;
        });
    }
}
