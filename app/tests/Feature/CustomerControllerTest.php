<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_customer_get_endpoint(): void
    {
        $response = $this->getJson('/api/customers');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }
}
