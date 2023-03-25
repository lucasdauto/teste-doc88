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

        $response = $this->getJson("/api/customers");

        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJson(function (AssertableJson $json) {
            for ($i = 0; $i < 3; $i++) {
                $json->whereAllType([
                    "{$i}.id" => "integer",
                    "{$i}.name" => "string",
                    "{$i}.email" => "string",
                    "{$i}.phone" => "string",
                    "{$i}.birthdate" => "string",
                    "{$i}.address" => "string",
                    "{$i}.complement" => "string|null",
                    "{$i}.neighborhood" => "string",
                    "{$i}.city" => "string",
                    "{$i}.zip_code"=> "string"
                ]);

                $json->where("{$i}.birthdate", function ($value) {
                    return (new \DateTime($value))->format("Y-m-d") === $value;
                });
            }
        });
    }
}
