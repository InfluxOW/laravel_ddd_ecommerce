<?php

namespace App\Domains\Generic\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Torann\GeoIP\Facades\GeoIP;

trait MocksGeoIPRequests
{
    use WithFaker;

    public function mockGeoIP(): void
    {
        $location = GeoIP::getService()->hydrate([
            'ip' => $this->faker->ipv4,
            'iso_code' => Str::random(3),
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'state' => Str::random(2),
            'state_name' => $this->faker->word,
            'postal_code' => $this->faker->postcode,
            'lat' => $this->faker->latitude,
            'lon' => $this->faker->longitude,
            'timezone' => $this->faker->timezone,
            'continent' => $this->faker->word,
        ]);

        /** @phpstan-ignore-next-line */
        GeoIP::partialMock()->shouldReceive('getLocation')->andReturn($location);
    }
}
