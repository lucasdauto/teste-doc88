<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_get_products_endpoint(): void
    {
        $products = Product::factory(3)->create();

        $response = $this->getJson("/api/products");

        $response->assertOk();
        $response->assertJsonCount(3);

        $response->assertJson(function (AssertableJson $json) use ($products) {
            for ($i = 0; $i < 3; $i++) {
                $json->hasAll([
                    "{$i}.id",
                    "{$i}.name",
                    "{$i}.price",
                    "{$i}.photo",
                    "{$i}.created_at",
                    "{$i}.updated_at",
                ])->etc();

                $json->whereAllType([
                    "{$i}.id" => "integer",
                    "{$i}.name" => "string",
                    "{$i}.photo" => "string",
                ]);
            }

            $product = $products->last();

            $json->whereAll([
                "2.id" => $product->id,
                "2.name" => $product->name,
                "2.price" => $product->price,
                "2.photo" => $product->photo,
            ]);

        });
    }

    public function test_get_single_product_endpoint()
    {
        $product = Product::factory(1)->createOne();

        $response = $this->getJson('/api/products/' . $product->id);
        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) use ($product) {
            $json->hasAll([
                "id",
                "name",
                "price",
                "photo",
            ])->etc();

            $json->whereAllType([
                "id" => "integer",
                "name" => "string",
                "photo" => "string",
            ]);

            $json->whereAll([
                "id" => $product->id,
                "name" => $product->name,
                "photo" => $product->photo,
                "price" => $product->price
            ]);
        });
    }

    public function test_get_should_validate_when_product_doesnt_exist_endpoint()
    {
        $response = $this->getJson('/api/products/1');
        $response->assertStatus(404);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message']);
            $json->whereAll(['message' => 'Product not found'])
                ->whereType('message', 'string');
        });
    }

    public function test_create_product_post_endpoint(): void
    {
        $product = Product::factory(1)->createOne()->toArray();

        $response = $this->postJson('/api/products', $product);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) use ($product) {

            $json->hasAll([
                "id",
                "name",
                "price",
                "photo",
                "created_at",
                "updated_at",
            ])->etc();

            $json->whereAll([
                "name" => $product['name'],
                "price" => $product['price'],
                "photo" => $product['photo'],
            ])->etc();
        });
    }

    public function test_post_should_validate_when_create_a_invalid_product(): void
    {
        $response = $this->postJson('/api/products', []);

        $response->assertStatus(422);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors']);
        });
    }

    public function test_update_product_put_endpoint(): void
    {
        $product = [
            "name" => "Pastel de frango",
            "price" => "5.50",
            "photo" => "https://picsum.photos/200/300"
        ];
        $oldProduct = Product::factory(1)->createOne()->toArray();

        $response = $this->putJson('/api/products/' . $oldProduct['id'], $product);

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) use ($product) {

            $json->hasAll([
                "id",
                "name",
                "price",
                "photo",
                "created_at",
                "updated_at",
            ])->etc();

            $json->whereAll([
                "name" => $product['name'],
                "price" => $product['price'],
                "photo" => $product['photo'],
            ])->etc();
        });
    }

    public function test_update_should_validate_when_update_a_invalid_product(): void
    {
        $product = [
            "name" => "Pastel de frango",
            "photo" => "https://picsum.photos/200/300"
        ];


        $oldProduct = Product::factory(1)->createOne()->toArray();
        $response = $this->putJson('/api/products/3', $oldProduct);

        $response->assertNotFound();

        $response = $this->putJson('/api/products/' . $oldProduct["id"], $product);
        $response->assertUnprocessable();

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors']);
        });

        $response = $this->putJson('/api/products/' . $oldProduct["id"], []);
        $response->assertUnprocessable();

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors']);
        });
    }

    public function test_delete_product_delete_endpoint(): void
    {
        $product = Product::factory(1)->createOne()->toArray();
        $id = $product['id'];

        $response = $this->deleteJson('/api/products/' . $id);
        $response->assertStatus(204);

        $responseError = $this->getJson("/api/products/" . $id);
        $responseError->assertStatus(404);
    }

    public function test_softDelete_in_product()
    {
        $product = Product::factory(1)->createOne()->toArray();
        $id = $product['id'];

        $productQuery = Product::find($id);
        $productQuery->delete();

        $this->assertSoftDeleted($productQuery);
    }
}
