<?php

namespace App\Domains\Users\Tests\Feature\Api;

use App\Application\Tests\TestCase;
use App\Domains\Generic\Models\ConfirmationToken;
use App\Domains\Users\Events\EmailVerificationSucceeded;
use App\Domains\Users\Models\User;
use App\Domains\Users\Notifications\EmailVerificationNotification;
use Carbon\Carbon;
use Illuminate\Routing\Middleware\ThrottleRequestsWithRedis;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

final class EmailVerificationControllerTest extends TestCase
{
    private static User $user;

    private static string $password;

    protected function setUpOnce(): void
    {
        $password = 'password';
        /** @var User $user */
        $user = User::factory()->unverified()->create(['password' => bcrypt($password)]);

        self::$password = $password;
        self::$user = $user;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            ThrottleRequestsWithRedis::class,
        ]);

        Notification::fake();
    }

    /** @test */
    public function a_user_can_verify_his_email_with_correct_token_from_email(): void
    {
        $this->assertNull(self::$user->email_verified_at);

        $token = $this->getConfirmationToken();

        Event::fake();
        $this->post(route('user.verify.email', ['token' => $token->token, 'email' => self::$user->email]))->assertOk();
        Event::assertDispatched(EmailVerificationSucceeded::class);

        $this->assertNotNull(self::$user->refresh()->email_verified_at);
    }

    /** @test */
    public function a_user_can_not_verify_his_email_with_invalid_token(): void
    {
        $token = $this->getConfirmationToken();

        // Wrong token
        $this->post(route('user.verify.email', ['token' => 'wrong_token', 'email' => self::$user->email]))->assertStatus(Response::HTTP_NOT_FOUND);

        // Wrong email
        $this->post(route('user.verify.email', ['token' => $token->token, 'email' => 'wrong_email@mail.com']))->assertStatus(Response::HTTP_NOT_FOUND);

        // Expired token
        $now = Carbon::now();
        $this->travelTo($token->expires_at->addMinute());
        $this->post(route('user.verify.email', ['token' => $token->token, 'email' => self::$user->email]))->assertStatus(Response::HTTP_NOT_FOUND);
        $this->travelTo($now);

        // Previous token
        $previousToken = $token;
        $this->travelTo($now->addMinute());
        $token = $this->getConfirmationToken();
        $this->post(route('user.verify.email', ['token' => $previousToken->token, 'email' => self::$user->email]))->assertStatus(Response::HTTP_NOT_FOUND);

        // Used token
        $token->used_at = $now;
        $token->save();
        $this->post(route('user.verify.email', ['token' => $token->token, 'email' => self::$user->email]))->assertStatus(Response::HTTP_NOT_FOUND);

        // Email is already verified
        $this->post(route('user.verify.email', ['token' => $token->token, 'email' => self::$user->email]))->assertStatus(Response::HTTP_NOT_FOUND);
    }

    private function getConfirmationToken(): ConfirmationToken
    {
        $this->withoutRecaptcha();
        $this->post(route('login'), ['email' => self::$user->email, 'password' => self::$password])->assertForbidden();

        $token = null;
        Notification::assertSentTo(self::$user, EmailVerificationNotification::class, static function (EmailVerificationNotification $notification) use (&$token): bool {
            $token = $notification->getToken();

            return true;
        });
        /** @var ConfirmationToken $token */
        $this->assertNotNull($token);

        return $token;
    }
}
