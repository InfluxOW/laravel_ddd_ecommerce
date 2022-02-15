<?php

namespace App\Domains\Users\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Domains\Users\Models\User;
use App\Domains\Users\Notifications\EmailVerificationNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;

final class RegisterControllerTest extends TestCase
{
    protected array $validData;

    protected function setUp(): void
    {
        parent::setUp();

        $password = 'password';

        $this->validData = array_merge(
            Arr::only(User::factory()->make()->attributesToArray(), ['name', 'email']),
            ['password' => $password, 'password_confirmation' => $password],
        );

        Notification::fake();
    }

    /** @test */
    public function a_user_can_register_with_valid_data(): void
    {
        $userData = Arr::only($this->validData, ['name', 'email']);

        $this->assertDatabaseMissing('users', $userData);
        $this->post(route('register'), $this->validData)
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
        $this->post(route('register'), $this->validData)->assertCreated();
        $this->post(route('register'), $this->validData)->assertUnprocessable();

        $user = User::query()->where('email', $this->validData['email'])->first();
        $this->assertNotNull($user);

        Notification::assertSentToTimes($user, EmailVerificationNotification::class);
    }
}
