<?php

namespace App\Domains\Feedback\Database\Factories;

use App\Domains\Feedback\Models\Feedback;
use App\Domains\Users\Models\User;
use App\Infrastructure\Abstracts\Database\Factory;
use Illuminate\Support\Collection;

final class FeedbackFactory extends Factory
{
    private const USERS_CACHE_KEY = 'USERS';

    protected $model = Feedback::class;

    private Collection $users;

    protected function setUp(): void
    {
        $this->users = $this->cache->remember(self::USERS_CACHE_KEY, fn (): Collection => User::query()->get(['id', 'name', 'email', 'phone']));
    }

    public function definition(): array
    {
        return self::addTimestamps([
            'ip' => $this->faker->boolean(10) ? null : $this->faker->ipv4,
            'text' => $this->faker->realTextBetween(200, 1000),
            'is_reviewed' => $this->faker->boolean(60),
            'created_at' => $this->faker->dateTimeBetween('-1 year'),
        ]);
    }

    public function configure(): self
    {
        return $this->afterMaking(function (Feedback $feedback): void {
            $user = $this->users->random();

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
