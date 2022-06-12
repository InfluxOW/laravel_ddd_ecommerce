<?php

namespace App\Domains\Users\Tests\Feature;

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
    private User $user;
    private string $password;

    protected function setUp(): void
    {
        parent::setUp();

        $password = 'password';
        /** @var User $user */
        $user = User::factory()->unverified()->create(['password' => bcrypt($password)]);

        $this->password = $password;
        $this->user = $user;

        $this->withoutMiddleware([
            ThrottleRequestsWithRedis::class,
        ]);

        Notification::fake();
    }

    /** @test */
    public function a_user_can_verify_his_email_with_correct_token_from_email(): void
    {
        $this->assertNull($this->user->email_verified_at);

        $token = $this->getConfirmationToken();

        Event::fake();
        $this->post(route('user.verify.email', ['token' => $token->token, 'email' => $this->user->email]))->assertNoContent();
        Event::assertDispatched(EmailVerificationSucceeded::class);

        $this->assertNotNull($this->user->refresh()->email_verified_at);
    }

    /** @test */
    public function a_user_can_not_verify_his_email_with_invalid_token(): void
    {
        $token = $this->getConfirmationToken();

        // Wrong token
        $this->post(route('user.verify.email', ['token' => 'wrong_token', 'email' => $this->user->email]))->assertStatus(Response::HTTP_NOT_FOUND);

        // Wrong email
        $this->post(route('user.verify.email', ['token' => $token->token, 'email' => 'wrong_email@mail.com']))->assertStatus(Response::HTTP_NOT_FOUND);

        // Expired token
        $now = Carbon::now();
        $this->travelTo($token->expires_at->addMinute());
        $this->post(route('user.verify.email', ['token' => $token->token, 'email' => $this->user->email]))->assertStatus(Response::HTTP_NOT_FOUND);
        $this->travelTo($now);

        // Previous token
        $previousToken = $token;
        $this->travelTo($now->addMinute());
        $token = $this->getConfirmationToken();
        $this->post(route('user.verify.email', ['token' => $previousToken->token, 'email' => $this->user->email]))->assertStatus(Response::HTTP_NOT_FOUND);

        // Used token
        $token->used_at = $now;
        $token->save();
        $this->post(route('user.verify.email', ['token' => $token->token, 'email' => $this->user->email]))->assertStatus(Response::HTTP_NOT_FOUND);

        // Email is already verified
        $this->post(route('user.verify.email', ['token' => $token->token, 'email' => $this->user->email]))->assertStatus(Response::HTTP_NOT_FOUND);
    }

    private function getConfirmationToken(): ConfirmationToken
    {
        $this->post(route('login'), ['email' => $this->user->email, 'password' => $this->password])->assertForbidden();

        $token = null;
        Notification::assertSentTo($this->user, EmailVerificationNotification::class, static function (EmailVerificationNotification $notification) use (&$token): bool {
            $token = $notification->getToken();

            return true;
        });
        /** @var ConfirmationToken $token */
        $this->assertNotNull($token);

        return $token;
    }
}
