<?php

namespace Database\Seeders;

use App\Models\Product;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pastel = "Pastel de ";
        $products = [
            [
                "name" => "{$pastel} carne",
                "photo" => $this->faker->imageUrl(),
                "price" => 10.00,
            ],
            [
                "name" => "{$pastel} queijo",
                "photo" => $this->faker->imageUrl(),
                "price" => 10.00,
            ],
            [
                "name" => "{$pastel} frango",
                "photo" => $this->faker->imageUrl(),
                "price" => 10.00,
            ],
            [
                "name" => "{$pastel} calabresa",
                "photo" => $this->faker->imageUrl(),
                "price" => 10.00,
            ],
            [
                "name" => "{$pastel} palmito",
                "photo" => $this->faker->imageUrl(),
                "price" => 10.00,
            ],
            [
                "name" => "Coca-Cola",
                "photo" => $this->faker->imageUrl(),
                "price" => 5.50,
            ],
            [
                "name" => "Coca-Cola Zero",
                "photo" => $this->faker->imageUrl(),
                "price" => 5.50,
            ],
            [
                "name" => "Caldo de cana",
                "photo" => $this->faker->imageUrl(),
                "price" => 5.50,
            ],
        ];

        foreach ($products as $product) {
            Product::factory()->create($product);
        }
    }
}
