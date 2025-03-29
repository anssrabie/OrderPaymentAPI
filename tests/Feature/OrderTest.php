<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper method to authenticate a user.
     */
    private function authenticateUser(): User
    {
        // Create and authenticate the test user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user);
        return $user;
    }

    /**
     * Test the store route to create an order.
     *
     * @return void
     */
    public function test_create_order()
    {
        $this->authenticateUser(); // Authenticate user

        // Create test products to add to the order
        $product = Product::factory()->create();

        // Prepare the request data
        $data = [
            'products' => [
                [
                    'id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ];

        // Make a POST request to create the order
        $response = $this->postJson('/api/v1/orders', $data, [
            'X-API-KEY' => config('app.api_key'),
        ]);

        // Assert the response is successful and structure is correct
        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'total_price',
                    'status',
                    'created_at',
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'unit_price',
                            'quantity',
                            'total_price',
                        ]
                    ]
                ],
                'message',
                'status',
                'code',
            ])
            ->assertJsonFragment([
                'message' => 'Order has been created successfully',
                'status' => true,
                'code' => 201,
            ]);
    }

    /**
     * Test fetching all orders with pagination.
     */
    public function test_get_all_orders()
    {
        $this->authenticateUser();
        $response = $this->getJson('/api/v1/orders', [
            'X-API-KEY' => config('app.api_key'),
        ]);
        $response->assertStatus(200);
    }

    /**
     * Test deleting an order.
     */
    public function test_delete_order()
    {
        $this->authenticateUser();

        $this->withoutExceptionHandling();

        $product = Product::factory()->create();

        $orderData = [
            'products' => [
                [
                    'id' => $product->id,
                    'quantity' => 1,
                ]
            ]
        ];

        $createResponse = $this->postJson('/api/v1/orders', $orderData, [
            'X-API-KEY' => config('app.api_key'),
        ]);

        $orderId = $createResponse->json('data.id');

        $this->assertNotNull($orderId, 'Order was not created successfully.');

        $response = $this->deleteJson("/api/v1/orders/{$orderId}", [], [
            'X-API-KEY' => config('app.api_key'),
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Order has been deleted successfully',
                'status' => true,
            ]);
    }


    public function test_show_order()
    {
        $this->authenticateUser();

        $product = Product::factory()->create();
        $orderData = [
            'products' => [
                [
                    'id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ];

        $createResponse = $this->postJson('/api/v1/orders', $orderData, [
            'X-API-KEY' => config('app.api_key'),
        ]);

        $orderId = $createResponse->json('data.id');
        $this->assertNotNull($orderId, 'Order was not created successfully.');


        $response = $this->getJson("/api/v1/orders/{$orderId}", [
            'X-API-KEY' => config('app.api_key'),
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'total_price',
                    'status',
                    'created_at',
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'unit_price',
                            'quantity',
                            'total_price',
                        ]
                    ]
                ],
                'message',
                'status',
                'code',
            ]);
    }


    public function test_update_order()
    {
         $this->authenticateUser();


        $product = Product::factory()->create();
        $newProduct = Product::factory()->create(); // منتج جديد للتحديث


        $orderData = [
            'products' => [
                [
                    'id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ];

        $createResponse = $this->postJson('/api/v1/orders', $orderData, [
            'X-API-KEY' => config('app.api_key'),
        ]);

        $orderId = $createResponse->json('data.id');
        $this->assertNotNull($orderId, 'Order was not created successfully.');


        $updateData = [
            'products' => [
                [
                    'id' => $newProduct->id,
                    'quantity' => 3,
                ]
            ]
        ];

        $response = $this->putJson("/api/v1/orders/{$orderId}", $updateData, [
            'X-API-KEY' => config('app.api_key'),
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Order has been updated successfully',
                'status' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'total_price',
                    'status',
                    'created_at',
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'unit_price',
                            'quantity',
                            'total_price',
                        ]
                    ]
                ],
                'message',
                'status',
                'code',
            ]);
    }



}
