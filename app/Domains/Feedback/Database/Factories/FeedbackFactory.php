<?php

namespace App\Domains\Feedback\Database\Factories;

use App\Domains\Common\Database\Factory;
use App\Domains\Feedback\Models\Feedback;
use App\Domains\Users\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    private Collection $users;

    protected function setUp(): void
    {
        $this->users = Cache::rememberInArray(
            json_encode([self::class => 'users'], JSON_THROW_ON_ERROR),
            static fn (): Collection => User::query()->get(['id', 'name', 'email', 'phone'])
        );
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
