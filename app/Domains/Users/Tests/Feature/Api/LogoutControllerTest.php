<?php

namespace App\Domains\Users\Tests\Feature\Api;

use App\Application\Tests\TestCase;
use App\Domains\Users\Models\User;
use Illuminate\Support\Facades\Event;

final class LogoutControllerTest extends TestCase
{
    private static User $user;

    protected function setUpOnce(): void
    {
        $password = 'password';
        /** @var User $user */
        $user = User::factory()->create(['password' => bcrypt($password)]);

        self::$user = $user;
    }

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function an_unauthenticated_user_cannot_logout(): void
    {
        $this->post(route('logout'))->assertUnauthorized();
    }

    /** @test
     */
    public function an_authenticated_user_can_logout(): void
    {
        $this->actingAs(self::$user)->post(route('logout'))->assertOk();
    }
}
