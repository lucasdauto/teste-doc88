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

        $response->assertStatus(200);
        $response->assertJsonCount(3);

    }
}
