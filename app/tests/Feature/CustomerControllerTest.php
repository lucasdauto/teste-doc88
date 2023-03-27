<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_get_customers_endpoint(): void
    {
        $customers = Customer::factory(3)->create(array('deleted_at' => null));

        $response = $this->getJson("/api/customers");

        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJson(function (AssertableJson $json) use ($customers) {
            for ($i = 0; $i < 3; $i++) {
                $json->hasAll([
                    "{$i}.id",
                    "{$i}.name",
                    "{$i}.email",
                    "{$i}.phone",
                    "{$i}.birthdate",
                    "{$i}.address",
                    "{$i}.neighborhood",
                    "{$i}.city",
                    "{$i}.zip_code",
                    "{$i}.created_at",
                    "{$i}.updated_at",
                ])->etc();

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
                    "{$i}.zip_code" => "string"
                ]);

                $json->where("{$i}.birthdate", function ($value) {
                    return (new DateTime($value))->format("Y-m-d") === $value;
                });

                $customer = $customers->first();

                $json->whereAll([
                    "0.id" => $customer->id,
                    "0.name" => $customer->name,
                    "0.email" => $customer->email,
                    "0.phone" => $customer->phone,
                    "0.birthdate" => $customer->birthdate,
                    "0.address" => $customer->address,
                    "0.complement" => $customer->complement,
                    "0.neighborhood" => $customer->neighborhood,
                    "0.city" => $customer->city,
                    "0.zip_code" => $customer->zip_code
                ]);
            }
        });
    }

    public function test_get_single_custumer_endpoint()
    {
        $customer = Customer::factory(1)->createOne();

        $response = $this->getJson("/api/customers/" . $customer->id);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use ($customer) {

            $json->hasAll([
                "id",
                "name",
                "email",
                "phone",
                "birthdate",
                "address",
                "neighborhood",
                "city",
                "zip_code",
                "complement",
                "created_at",
                "updated_at",
            ])->etc();

            $json->whereAllType([
                "id" => "integer",
                "name" => "string",
                "email" => "string",
                "phone" => "string",
                "birthdate" => "string",
                "address" => "string",
                "neighborhood" => "string",
                "city" => "string",
                "complement" => "string|null",
                "zip_code" => "string",
            ]);

            $json->whereAll([
                "id" => $customer->id,
                "name" => $customer->name,
                "email" => $customer->email,
                "phone" => $customer->phone,
                "birthdate" => $customer->birthdate,
                "address" => $customer->address,
                "neighborhood" => $customer->neighborhood,
                "city" => $customer->city,
                "zip_code" => $customer->zip_code,
            ]);


        });
    }

    public function test_create_customer_post_endpoint(): void
    {
        $customer = Customer::factory(1)->makeOne()->toArray();

        $response = $this->postJson('/api/customers', $customer);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) use ($customer) {

            $json->hasAll([
                "id",
                "name",
                "email",
                "phone",
                "birthdate",
                "address",
                "neighborhood",
                "city",
                "zip_code",
                "created_at",
                "updated_at",
            ])->etc();

            $json->whereAll([
                "name" => $customer['name'],
                "email" => $customer['email'],
                "phone" => $customer['phone'],
                "birthdate" => $customer['birthdate'],
                "address" => $customer['address'],
                "neighborhood" => $customer['neighborhood'],
                "city" => $customer['city'],
                "zip_code" => $customer['zip_code'],
            ])->etc();
        });
    }

    public function test_post_should_validate_when_create_a_invalid_customer()
    {
        $response = $this->postJson('/api/customers', []);

        $response->assertStatus(422);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message','errors']);
        });
    }

    public function test_update_customer_put_endpoint(): void
    {

        $customer = [
          "name"=> "Marv",
          "email"=> "mgower0@flickr.com",
          "phone"=> "(29) 2239320",
          "birthdate"=> "1990-01-01",
          "address"=> "Anzinger",
          "neighborhood"=> "Trail",
          "city"=> "Grevená",
          "zip_code"=> "54868-564"
        ];

        $oldCustomer = Customer::factory(1)->createOne();

        $response = $this->putJson('/api/customers/' . $oldCustomer->id, $customer);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use ($customer, $oldCustomer) {
            $json->hasAll([
                "id",
                "name",
                "email",
                "phone",
                "birthdate",
                "address",
                "neighborhood",
                "city",
                "zip_code",
                "created_at",
                "updated_at",
            ]);

            $json->whereAll([
                "name" => $customer["name"],
                "email" => $customer["email"],
                "phone" => $customer["phone"],
                "birthdate" => $customer["birthdate"],
                "address" => $customer["address"],
                "neighborhood" => $customer["neighborhood"],
                "city" => $customer["city"],
                "zip_code" => $customer["zip_code"],
            ])->etc();
        });
    }

    public function test_put_should_validate_when_update_a_invalid_customer()
    {
        $customer = [
            "name"=> "Marv",
            "email"=> "mgower0@flickr.com",
            "phone"=> "(29) 2239320",
            "birthdate"=> "01-01-1990",
            "address"=> "Anzinger",
            "neighborhood"=> "Trail",
            "city"=> "Grevená",
            "zip_code"=> "0054868-564"
        ];

        $oldCustomer = Customer::factory(1)->createOne()->toArray();

        $response = $this->putJson('/api/customers/3', $oldCustomer);

        $response->assertStatus(422);

        $response = $this->putJson('/api/customers/' . $oldCustomer["id"], $customer);
        $response->assertStatus(422);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message','errors']);
        });

        $response = $this->putJson('/api/customers/' . $oldCustomer["id"], []);
        $response->assertStatus(422);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message','errors']);
        });
    }

    public function test_delete_customer_endpoint()
    {
        $customer = Customer::factory(1)->createOne();
        $id = $customer->id;

        $response = $this->deleteJson('/api/customers/' . $id);
        $response->assertStatus(204);

        $responseError = $this->getJson("/api/customers/" . $id);
        $responseError->assertStatus(404);

    }

    public function test_softDelete_in_customer()
    {
        $customer = Customer::factory(1)->createOne()->toArray();
        $id = $customer['id'];

        $customerQuery = Customer::find($id);
        $customerQuery->delete();

        $this->assertSoftDeleted($customerQuery);
    }

    public function test_between_customer_and_order(): void
    {
        $order = Order::factory()->make();
        $customer = Customer::first();
        $customer->orders()->save($order);
        $customer->refresh();

        $this->assertTrue($customer->orders->contains($order));

    }
}
