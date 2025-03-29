<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * User Register test.
     */
    public function test_register_user()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/v1/auth/register', $data,[
            'X-API-KEY' => config('app.api_key'),
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'token',
                ],
                'message',
                'status',
                'code',
            ])
            ->assertJsonFragment([
                'message' => 'Registration successful',
                'status' => true,
                'code' => 201,
            ]);
    }

    /**
     * User login test.
     */
    public function test_login_user()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $data = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/v1/auth/login', $data,[
            'X-API-KEY' => config('app.api_key'),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                    ],
                    'token',
                ],
                'message',
                'status',
                'code',
            ])
            ->assertJsonFragment([
                'message' => 'You have successfully logged in.',
                'status' => true,
                'code' => 200,
            ]);
    }


    /**
     * User logout test.
     */
    public function test_logout_user()
    {
        $token = $this->authenticateUser();

        $response = $this->postJson('/api/v1/auth/logout', [], [
            'Authorization' => "Bearer $token",
            'X-API-KEY' => config('app.api_key'),
        ]);

        $response->assertStatus(200);
    }


    private function authenticateUser(): string
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ],[
            'X-API-KEY' => config('app.api_key'),
        ]);

        return $response->json('data.token');
    }

}
