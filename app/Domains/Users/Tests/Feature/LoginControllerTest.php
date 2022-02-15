<?php

namespace App\Domains\Users\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Domains\Users\Events\EmailVerificationSucceeded;
use App\Domains\Users\Models\User;
use App\Domains\Users\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

final class LoginControllerTest extends TestCase
{
    public User $user;
    public string $password;

    protected function setUp(): void
    {
        parent::setUp();

        $password = 'password';
        /** @var User $user */
        $user = User::factory()->create(['password' => bcrypt($password)]);

        $this->password = $password;
        $this->user = $user;

        Notification::fake();
    }

    /** @test */
    public function a_user_cannot_login_with_wrong_credentials(): void
    {
        $this->post(route('login'), ['email' => $this->user->email, 'password' => 'wrong_password'])->assertUnprocessable();
    }

    /** @test */
    public function a_user_can_login_with_correct_credentials_and_confirmed_email(): void
    {
        $this->user->email_verified_at = null;
        $this->user->save();

        $this->post(route('login'), ['email' => $this->user->email, 'password' => $this->password])->assertForbidden();

        Notification::assertSentTo($this->user, EmailVerificationNotification::class, function (EmailVerificationNotification $notification): bool {
            Event::fake();
            $this->post(route('user.verify.email', ['token' => $notification->getTokenString(), 'email' => $this->user->email]))->assertNoContent();
            Event::assertDispatched(EmailVerificationSucceeded::class);

            return true;
        });

        $loginResponse = $this->post(route('login'), ['email' => $this->user->email, 'password' => $this->password])
            ->assertOk()
            ->assertJsonStructure(['access_token']);
        $accessToken = $loginResponse->json('access_token');

        /*
         * You should call any endpoint while being authenticated to
         * have your guard's user set.
         * */
        $this->withHeader('Authorization', "Bearer {$accessToken}")->get('products.index');

        $this->assertAuthenticatedAs($this->user, 'sanctum');
    }
}
