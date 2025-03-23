<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductOptionsTableSeeder extends Seeder
{
    public function run()
    {
        $productOptions = [
            // Sample Vegetables
            [
                'product_id' => 1,
                'option' => '100g',
                'price' => 1.99,
                'quantity' => 50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 1,
                'option' => '500g',
                'price' => 8.99,
                'quantity' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 1,
                'option' => '1kg',
                'price' => 15.99,
                'quantity' => 20,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Broccoli
            [
                'product_id' => 2,
                'option' => 'Per pcs',
                'price' => 2.50,
                'quantity' => 50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 2,
                'option' => '500g',
                'price' => 4.50,
                'quantity' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Carrot
            [
                'product_id' => 3,
                'option' => '300g',
                'price' => 1.90,
                'quantity' => 97,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 3,
                'option' => '1kg',
                'price' => 5.90,
                'quantity' => 50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Spring Onion
            [
                'product_id' => 4,
                'option' => '200g',
                'price' => 1.20,
                'quantity' => 60,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Shallot
            [
                'product_id' => 5,
                'option' => '200g',
                'price' => 1.50,
                'quantity' => 57,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Ginger
            [
                'product_id' => 6,
                'option' => '200g',
                'price' => 1.90,
                'quantity' => 87,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Strawberry
            [
                'product_id' => 7,
                'option' => 'Per pack',
                'price' => 14.90,
                'quantity' => 56,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Black Pepper
            [
                'product_id' => 8,
                'option' => '400g',
                'price' => 10.90,
                'quantity' => 68,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Ground Black Pepper
            [
                'product_id' => 9,
                'option' => '400g',
                'price' => 10.90,
                'quantity' => 64,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // White Pepper
            [
                'product_id' => 10,
                'option' => '400g',
                'price' => 10.90,
                'quantity' => 57,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Ground White Pepper
            [
                'product_id' => 11,
                'option' => '400g',
                'price' => 10.90,
                'quantity' => 74,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Avocado
            [
                'product_id' => 12,
                'option' => '500g',
                'price' => 10.50,
                'quantity' => 39,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Mandarin Oranges
            [
                'product_id' => 13,
                'option' => 'Per box',
                'price' => 18.90,
                'quantity' => 99,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Cabbages
            [
                'product_id' => 14,
                'option' => '400g',
                'price' => 3.70,
                'quantity' => 103,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Pak Choy
            [
                'product_id' => 15,
                'option' => 'Per pack',
                'price' => 2.90,
                'quantity' => 93,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Bitter Gourd
            [
                'product_id' => 16,
                'option' => '200g',
                'price' => 2.90,
                'quantity' => 37,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Mandarin Oranges (Clearance)
            [
                'product_id' => 17,
                'option' => 'Per box',
                'price' => 8.90,
                'quantity' => 19,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Avocado (Clearance)
            [
                'product_id' => 18,
                'option' => '500g',
                'price' => 4.50,
                'quantity' => 9,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Bitter Gourd (Clearance)
            [
                'product_id' => 19,
                'option' => '200g',
                'price' => 1.50,
                'quantity' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Cabbages (Clearance)
            [
                'product_id' => 20,
                'option' => '200g',
                'price' => 1.70,
                'quantity' => 23,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Pak Choy (Clearance)
            [
                'product_id' => 21,
                'option' => 'Per pack',
                'price' => 1.30,
                'quantity' => 12,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('product_options')->insert($productOptions);
    }
}