<?php

namespace App\Domains\Users\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Domains\Users\Models\User;

class LoginControllerTest extends TestCase
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
    public function a_user_cannot_login_with_wrong_credentials(): void
    {
        $this->post(route('login'), ['email' => $this->user->email, 'password' => 'wrong_password', 'remember' => false])
            ->assertUnprocessable();
    }

    /** @test */
    public function a_user_can_login_with_correct_credentials(): void
    {
        $this->post(route('login'), ['email' => $this->user->email, 'password' => $this->password, 'remember' => false])
            ->assertOk()
            ->assertJsonStructure(['user', 'access_token']);

        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function a_user_is_considered_authenticated_via_using_bearer_token(): void
    {
        $token = $this->user->createToken('access_token')->plainTextToken;

        $this->post(route('logout'), [], ['Authorization' => "Bearer {$token}"])
            ->assertOk();
    }
}
