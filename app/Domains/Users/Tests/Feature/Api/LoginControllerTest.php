<?php

namespace App\Domains\Users\Tests\Feature\Api;

use App\Application\Tests\TestCase;
use App\Domains\Generic\Tests\MocksGeoIPRequests;
use App\Domains\Users\Events\EmailVerificationSucceeded;
use App\Domains\Users\Models\User;
use App\Domains\Users\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;

final class LoginControllerTest extends TestCase
{
    use MocksGeoIPRequests;

    private static User $user;

    private static string $password;

    protected function setUpOnce(): void
    {
        $password = 'password';
        /** @var User $user */
        $user = User::factory()->create(['password' => bcrypt($password)]);

        self::$password = $password;
        self::$user = $user;
    }

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->withoutRecaptcha();
    }

    /** @test */
    public function a_user_cannot_login_with_wrong_credentials(): void
    {
        $this->login('wrong_password')->assertUnprocessable();
    }

    /** @test */
    public function a_user_can_login_with_correct_credentials_and_confirmed_email(): void
    {
        self::$user->email_verified_at = null;
        self::$user->save();

        $this->login()->assertForbidden();

        Notification::assertSentTo(self::$user, EmailVerificationNotification::class, function (EmailVerificationNotification $notification): bool {
            Event::fake();
            $this->post(route('user.verify.email', ['token' => $notification->getTokenString(), 'email' => self::$user->email]))->assertOk();
            Event::assertDispatched(EmailVerificationSucceeded::class);

            return true;
        });

        $this->mockGeoIP();

        $loginResponse = $this->login()
            ->assertOk()
            ->assertJsonStructure(['access_token']);
        $accessToken = $loginResponse->json('access_token');

        /*
         * You should call any endpoint while being authenticated to
         * have your guard's user set.
         * */
        $this->withHeader('Authorization', "Bearer {$accessToken}")->get('products.index');

        $this->assertAuthenticatedAs(self::$user, 'sanctum');
    }

    /** @test */
    public function user_login_history_is_updated_upon_login(): void
    {
        $this->mockGeoIP();

        $this->assertEquals(0, self::$user->loginHistory()->count());
        $this->login();
        $this->assertEquals(1, self::$user->loginHistory()->count());
    }

    private function login(?string $password = null): TestResponse
    {
        return $this->post(route('login'), ['email' => self::$user->email, 'password' => $password ?? self::$password]);
    }
}
