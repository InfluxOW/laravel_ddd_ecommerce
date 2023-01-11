<?php

namespace App\Domains\Admin\Database\Factories;

use App\Domains\Admin\Models\Admin;
use App\Domains\Common\Database\Factory;
use Illuminate\Support\Str;

final class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return self::addTimestamps([
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
    }
}
