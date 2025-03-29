<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateUser(): User
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    /**
     * Test payment processing.
     */
    public function test_user_can_process_payment()
    {
        $user = $this->authenticateUser();
        $order = Order::factory()->create(['user_id' => $user->id,'status' => OrderStatus::Confirmed->value]);
        $this->assertDatabaseHas('orders', ['id' => $order->id]);

        $data = [
            'order_id' => $order->id,
            'method' => PaymentMethod::CreditCard->value,
        ];

        $response = $this->postJson('/api/v1/payments', $data, [
            'X-API-KEY' => config('app.api_key'),
        ]);

        $response->assertStatus(201);
    }

    /**
     * Test fetching payments.
     */
    public function test_user_can_get_payments()
    {
        $this->authenticateUser();

        $response = $this->getJson('/api/v1/payments', [
            'X-API-KEY' => config('app.api_key'),
        ]);

        $response->assertStatus(200);
    }
}
