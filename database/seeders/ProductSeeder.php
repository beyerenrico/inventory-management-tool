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
        $products = [
            [
                'name' => 'Wireless Headphones',
                'sku' => 'WH-001',
                'description' => 'High-quality wireless headphones with noise cancellation',
                'price' => 129.99,
                'stock_quantity' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'USB-C Cable',
                'sku' => 'USBC-001',
                'description' => '1m USB-C to USB-C cable for fast charging',
                'price' => 19.99,
                'stock_quantity' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Smartphone Stand',
                'sku' => 'SS-001',
                'description' => 'Adjustable smartphone stand for desk use',
                'price' => 24.99,
                'stock_quantity' => 75,
                'is_active' => true,
            ],
            [
                'name' => 'Bluetooth Speaker',
                'sku' => 'BS-001',
                'description' => 'Portable Bluetooth speaker with 10h battery life',
                'price' => 89.99,
                'stock_quantity' => 30,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
