<?php

namespace App\Domain\Users\Tests\Feature;

use App\Domain\Users\Models\User;
use Illuminate\Support\Arr;
use App\Application\Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    public array $validData;

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
        $this->post(route('register'), $this->validData)
            ->assertOk()
            ->assertJsonStructure(['user', 'access_token']);
    }

    /** @test */
    public function a_user_cannot_register_twice(): void
    {
        $this->post(route('register'), $this->validData);

        $this->post(route('register'), $this->validData)
            ->assertUnprocessable();
    }
}
