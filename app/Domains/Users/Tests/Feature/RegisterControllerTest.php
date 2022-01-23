<?php

namespace App\Domains\Users\Tests\Feature;

use App\Application\Tests\TestCase;
use App\Domains\Users\Models\User;
use Illuminate\Support\Arr;

class RegisterControllerTest extends TestCase
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
    }

    /** @test */
    public function a_user_can_register_with_valid_data(): void
    {
        $this->assertDatabaseMissing('users', Arr::only($this->validData, ['name', 'email']));

        $this->post(route('register'), $this->validData)
            ->assertOk()
            ->assertJsonStructure(['user', 'access_token']);

        $this->assertDatabaseHas('users', Arr::only($this->validData, ['name', 'email']));
    }

    /** @test */
    public function a_user_cannot_register_twice(): void
    {
        $this->post(route('register'), $this->validData);

        $this->post(route('register'), $this->validData)
            ->assertUnprocessable();
    }
}
