<?php

namespace App\Domains\Users\Tests\Feature\Api;

use App\Application\Tests\TestCase;
use App\Domains\Users\Models\User;
use App\Domains\Users\Notifications\EmailVerificationNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;

final class RegisterControllerTest extends TestCase
{
    protected static array $validData;

    protected function setUpOnce(): void
    {
        $password = 'password';

        self::$validData = array_merge(
            Arr::only(User::factory()->make()->attributesToArray(), ['name', 'email']),
            ['password' => $password, 'password_confirmation' => $password],
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->withoutRecaptcha();
    }

    /** @test */
    public function a_user_can_register_with_valid_data(): void
    {
        $userData = Arr::only(self::$validData, ['name', 'email']);

        $this->assertDatabaseMissing('users', $userData);
        $this->post(route('register'), self::$validData)
            ->assertCreated()
            ->assertJsonStructure(['message']);
        $this->assertDatabaseHas('users', $userData);

        $user = User::query()->where('email', $userData['email'])->first();
        $this->assertNotNull($user);

        Notification::assertSentTo($user, EmailVerificationNotification::class);
    }

    /** @test */
    public function a_user_cannot_register_twice(): void
    {
        $this->post(route('register'), self::$validData)->assertCreated();
        $this->post(route('register'), self::$validData)->assertUnprocessable();

        $user = User::query()->where('email', self::$validData['email'])->first();
        $this->assertNotNull($user);

        Notification::assertSentToTimes($user, EmailVerificationNotification::class);
    }
}
