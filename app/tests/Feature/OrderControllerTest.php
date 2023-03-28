<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
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
}
