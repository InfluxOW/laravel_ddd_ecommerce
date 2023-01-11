<?php

namespace App\Components\Addressable\Database\Factories;

use App\Components\Addressable\Models\Address;
use App\Domains\Common\Database\Factory;
use Squire\Models\Country;

final class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        $country = Country::query()->inRandomOrder()->first(['id']);
        $region = $country?->regions->first();

        return self::addTimestamps([
            'zip' => $this->faker->postcode(),
            'country' => $country?->id,
            'region' => $region?->id,
            'city' => $this->faker->city(),
            'street' => $this->faker->streetAddress(),
        ]);
    }
}
