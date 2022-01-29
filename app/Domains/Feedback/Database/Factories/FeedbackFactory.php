<?php

namespace App\Domains\Feedback\Database\Factories;

use App\Domains\Feedback\Models\Feedback;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

final class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    public function definition(): array
    {
        return [
            'ip' => $this->faker->boolean(10) ? null : $this->faker->ipv4,
            'text' => $this->faker->realTextBetween(200, 1000),
            'is_reviewed' => $this->faker->boolean(60),
            'created_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
    }

    public function configure(): self
    {
        return $this->afterMaking(function (Feedback $feedback): void {
            $user = $this->faker->boolean ? null : User::query()->inRandomOrder()->first();

            $username = $this->faker->name();
            $email = $this->faker->safeEmail();
            $phone = $this->faker->e164PhoneNumber();
            if (isset($user)) {
                $username = $user->name;
                $email = $user->email;
                $phone = $user->phone;
            }

            $feedback->user()->associate($user);
            $feedback->username = $username;
            $feedback->email = $email;
            $feedback->phone = $phone;
        });
    }
}
