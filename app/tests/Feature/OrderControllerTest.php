<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_get_orders_endpoint(): void
    {
        $customers = Customer::factory()->count(10)->create();

        foreach ($customers as $customer) {
            $customer->orders()->createMany(Order::factory()->count(rand(1, 5))->make()->toArray());
        }

        $response = $this->getJson('/api/orders');
        $response->assertStatus(200);

        foreach ($customers as $customer) {
            $this->assertTrue($customer->orders->isNotEmpty());
        }

        foreach ($customers as $customer) {
            foreach ($customer->orders as $order) {
                $this->assertEquals($customer->id, $order->customer_id);
            }
        }
    }

    public function test_get_single_order_endpoint(): void
    {
        $customer = Customer::factory()->create();
        $order1 = $customer->orders()->create();
        $order2 = $customer->orders()->create();

        $product1 = Product::factory()->create([
            'name' => 'Product 1',
            'price' => 5.50,
            'photo' => 'https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png',
        ]);

        $product2 = Product::factory()->create([
            'name' => 'Product 2',
            'price' => 5.50,
            'photo' => 'https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png',
        ]);

        $order1->orderItems()->create([
            'product_id' => $product1->id,
            'quantity' => 2,
        ]);

        $order2->orderItems()->create([
            'product_id' => $product2->id,
            'quantity' => 5,
        ]);

        $response = $this->get("/api/orders/{$order1->id}");
        $response->assertStatus(200)
            ->assertJson([
                'id' => $order1->id,
                'customer_id' => $customer->id,
                'order_items' => [
                    [
                        'product_id' => $product1->id,
                        'quantity' => 2,
                        'product' => [
                            'id' => $product1->id,
                            'name' => $product1->name,
                            'price' => $product1->price,
                            'photo' => 'https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png',
                        ],
                    ],
                ],
            ]);

        $responseData = $response->decodeResponseJson();
        $this->assertNotEmpty($responseData['order_items'], 'There should be at least one order item');
    }

    public function test_create_order_with_items_post_endpoint()
    {
        Customer::factory()->create();

        Product::factory()->createMany([
            [
                'name' => 'Product 1',
                'price' => 5.50,
                'photo' => 'https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png',

            ],
            [
                'name' => 'Product 2',
                'price' => 5.50,
                'photo' => 'https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png',
            ]
        ]);

        $order = [
            'customer_id' => 1,
            'order_items' => [
                [
                    'product_id' => 1,
                    'quantity' => 2,
                ],
                [
                    'product_id' => 2,
                    'quantity' => 5,
                ]
            ]
        ];

        $response = $this->postJson('/api/orders', $order);
        $response->assertStatus(201);


    }
}
