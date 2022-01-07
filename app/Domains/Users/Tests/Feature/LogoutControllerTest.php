<?php

namespace App\Domains\Users\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Domains\Users\Models\User;

class LogoutControllerTest extends TestCase
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
    }

    /** @test */
    public function an_unauthenticated_user_cannot_logout(): void
    {
        $this->post(route('logout'))->assertUnauthorized();
    }

    /** @test
     */
    public function an_unauthenticated_user_can_logout(): void
    {
        $this->post(route('login'), ['email' => $this->user->email, 'password' => $this->password, 'remember' => false]);

        $this->post(route('logout'))
            ->assertOk();
    }
}
