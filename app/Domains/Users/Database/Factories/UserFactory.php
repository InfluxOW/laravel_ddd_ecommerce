<?php

namespace App\Domains\Users\Database\Factories;

use App\Components\Addressable\Models\Address;
use App\Domains\Users\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->boolean ? null : $this->faker->unique()->e164PhoneNumber(),
            'email_verified_at' => Carbon::now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(fn (User $user): iterable => $user->addresses()->saveMany(Address::factory()->count(app()->runningUnitTests() ? 1 : random_int(1, 3))->make()));
    }

    public function unverified(): self
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }
}
