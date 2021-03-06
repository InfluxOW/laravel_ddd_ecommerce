<?php

namespace App\Domains\Feedback\Tests\Feature\Api;

use App\Application\Tests\TestCase;
use App\Domains\Feedback\Models\Feedback;
use App\Domains\Feedback\Models\Settings\FeedbackSettings;
use App\Domains\Users\Database\Seeders\UserSeeder;
use App\Domains\Users\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

final class FeedbackControllerTest extends TestCase
{
    use WithFaker;

    protected static array $validData;

    protected static User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutRecaptcha();
    }

    protected function setUpOnce(): void
    {
        $this->seed([
            UserSeeder::class,
        ]);

        $settings = app(FeedbackSettings::class);
        $settings->feedback_limit_per_hour = 1;
        $settings->save();

        /** @var User $user */
        $user = User::query()->first();
        $this->assertNotNull($user);

        self::$validData = Arr::only(Feedback::factory()->make()->attributesToArray(), ['username', 'email', 'phone', 'text']);
        self::$user = $user;
    }

    /** @test */
    public function a_guest_can_post_feedback(): void
    {
        $this->assertDatabaseMissing('feedback', self::$validData);

        $this->post(route('feedback.store'), self::$validData)->assertOk();

        $this->assertDatabaseHas('feedback', self::$validData);
    }

    /** @test */
    public function a_guest_cannot_post_more_feedback_per_hour_than_limit_allows(): void
    {
        $now = Carbon::now();

        $this->post(route('feedback.store'), self::$validData)->assertOk();

        $this->travelTo($now->addMinutes(30));

        $this->post(route('feedback.store'), self::$validData)->assertForbidden();

        $this->travelTo($now->addMinutes(30));

        $this->post(route('feedback.store'), self::$validData)->assertOk();
    }

    /** @test */
    public function a_guest_can_post_feedback_without_limits_from_different_ips(): void
    {
        $now = Carbon::now();

        $this->setIp($this->faker->unique()->ipv4)->post(route('feedback.store'), self::$validData)->assertOk();

        $this->travelTo($now->addMinutes(30));

        $this->setIp($this->faker->unique()->ipv4)->post(route('feedback.store'), self::$validData)->assertOk();

        $this->travelTo($now->addMinutes(30));

        $this->setIp($this->faker->unique()->ipv4)->post(route('feedback.store'), self::$validData)->assertOk();
    }

    /** @test */
    public function a_guest_cannot_post_feedback_and_then_authenticate_to_post_again(): void
    {
        $this->post(route('feedback.store'), self::$validData)->assertOk();
        $this->actingAs(self::$user)->post(route('feedback.store'), self::$validData)->assertForbidden();
    }

    /** @test */
    public function an_authenticated_user_can_post_feedback_and_user_data_will_be_filled_in_automatically(): void
    {
        $now = Carbon::now();
        $actualData = array_merge(self::$validData, [
            'username' => self::$user->name,
            'email' => self::$user->email,
            'phone' => self::$user->phone,
        ]);

        $this->assertDatabaseMissing('feedback', $actualData);

        foreach ([self::$validData, Arr::only(self::$validData, ['text'])] as $validData) {
            $this->travelTo($now);

            $this
                ->setIp($this->faker->unique()->ipv4)
                ->actingAs(self::$user)
                ->post(route('feedback.store'), $validData)
                ->assertOk();

            $this->assertDatabaseHas('feedback', $actualData);

            $now->addHour();
        }
    }

    /** @test */
    public function an_authenticated_user_cannot_post_more_feedback_per_hour_than_limit_allows_even_from_different_ips(): void
    {
        $now = Carbon::now();

        $this
            ->setIp($this->faker->unique()->ipv4)
            ->actingAs(self::$user)
            ->post(route('feedback.store'), self::$validData)
            ->assertOk();

        $this->travelTo($now->addMinutes(30));

        $this
            ->setIp($this->faker->unique()->ipv4)
            ->actingAs(self::$user)
            ->post(route('feedback.store'), self::$validData)
            ->assertForbidden();

        $this->travelTo($now->addMinutes(30));

        $this
            ->setIp($this->faker->unique()->ipv4)
            ->actingAs(self::$user)
            ->post(route('feedback.store'), self::$validData)
            ->assertOk();
    }
}
