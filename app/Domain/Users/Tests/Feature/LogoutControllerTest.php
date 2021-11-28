<?php

namespace App\Domain\Users\Tests\Feature;

use App\Domain\Users\Models\User;
use App\Application\Tests\TestCase;

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
    public function an_unauthorized_user_cannot_logout(): void
    {
        $this->post(route('logout'))
            ->assertRedirect(route('login'));
    }

    /** @test
     */
    public function an_authorized_user_can_logout(): void
    {
        $this->post(route('login'), ['email' => $this->user->email, 'password' => $this->password]);

        $this->post(route('logout'))
            ->assertOk();
    }
}
