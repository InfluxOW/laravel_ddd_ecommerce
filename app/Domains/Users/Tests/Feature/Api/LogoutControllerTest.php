<?php

namespace App\Domains\Users\Tests\Feature\Api;

use App\Domains\Common\Tests\TestCase;
use App\Domains\Users\Models\User;
use Illuminate\Support\Facades\Event;

final class LogoutControllerTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $password = 'password';
        /** @var User $user */
        $user = User::factory()->create(['password' => bcrypt($password)]);

        $this->user = $user;

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
        $this->actingAs($this->user)->post(route('logout'))->assertOk();
    }
}
