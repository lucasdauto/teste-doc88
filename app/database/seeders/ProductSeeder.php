<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pastel = "Pastel de ";
        $products = [
            [
                "name" => "{$pastel} carne",
                "photo" => $this->fake()->imageUrl(),
                "price" => 10.00,
            ],
            [
                "name" => "{$pastel} queijo",
                "photo" => $this->fake()->imageUrl(),
                "price" => 10.00,
            ],
            [
                "name" => "{$pastel} frango",
                "photo" => $this->fake()->imageUrl(),
                "price" => 10.00,
            ],
            [
                "name" => "{$pastel} calabresa",
                "photo" => $this->fake()->imageUrl(),
                "price" => 10.00,
            ],
            [
                "name" => "{$pastel} palmito",
                "photo" => $this->fake()->imageUrl(),
                "price" => 10.00,
            ],
            [
                "name" => "Coca-Cola",
                "photo" => $this->fake()->imageUrl(),
                "price" => 5.50,
            ],
            [
                "name" => "Coca-Cola Zero",
                "photo" => $this->fake()->imageUrl(),
                "price" => 5.50,
            ],
            [
                "name" => "Caldo de cana",
                "photo" => $this->fake()->imageUrl(),
                "price" => 5.50,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
