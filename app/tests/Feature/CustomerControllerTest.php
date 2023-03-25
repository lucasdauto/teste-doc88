<?php

namespace Tests\Feature;

use App\Models\Customer;
use \Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     * @test
     */
    public function test_customer_get_endpoint(): void
    {
        $customer = Customer::factory(3)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJson(function (AssertableJson $json) {
            $json->whereType('0.id', 'integer');
            $json->whereType('0.name', 'string');
            $json->whereType('0.email', 'string');
            $json->whereType('0.phone', 'string');
            $json->whereType('0.birthdate', 'string');
            $json->where('0.birthdate', function($value) {
                return (new \DateTime($value))->format('Y-m-d') === $value;
            });
            $json->whereType('0.address', 'string');
            $json->whereType('0.complement', 'string|null');
            $json->whereType('0.neighborhood', 'string');
            $json->whereType('0.city', 'string');
            $json->whereType('0.zip_code', 'string');
        });
    }
}
