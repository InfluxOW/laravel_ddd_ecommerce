<?php

namespace App\Domain\Generic\Address\Database\Factories;

use App\Domain\Generic\Address\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Squire\Models\Country;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        $country = Country::query()->inRandomOrder()->first();
        $region = $country?->regions->first();

        return [
            'zip' => $this->faker->postcode(),
            'country' => $country?->id,
            'region' => $region?->id,
            'city' => $this->faker->city(),
            'street' => $this->faker->streetAddress(),
        ];
    }
}
