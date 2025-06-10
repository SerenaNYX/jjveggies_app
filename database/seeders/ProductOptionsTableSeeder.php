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
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 1,
                'option' => '500g',
                'price' => 8.99,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 1,
                'option' => '1kg',
                'price' => 15.99,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Broccoli
            [
                'product_id' => 2,
                'option' => 'Per pcs',
                'price' => 2.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 2,
                'option' => '500g',
                'price' => 4.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Carrot
            [
                'product_id' => 3,
                'option' => '300g',
                'price' => 1.90,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 3,
                'option' => '1kg',
                'price' => 5.90,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Spring Onion
            [
                'product_id' => 4,
                'option' => '200g',
                'price' => 1.20,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Shallot
            [
                'product_id' => 5,
                'option' => '200g',
                'price' => 1.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Ginger
            [
                'product_id' => 6,
                'option' => '200g',
                'price' => 1.90,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Strawberry
            [
                'product_id' => 7,
                'option' => 'Per pack',
                'price' => 14.90,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Black Pepper
            [
                'product_id' => 8,
                'option' => '400g',
                'price' => 10.90,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Ground Black Pepper
            [
                'product_id' => 9,
                'option' => '400g',
                'price' => 10.90,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // White Pepper
            [
                'product_id' => 10,
                'option' => '400g',
                'price' => 10.90,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Ground White Pepper
            [
                'product_id' => 11,
                'option' => '400g',
                'price' => 10.90,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Avocado
            [
                'product_id' => 12,
                'option' => '500g',
                'price' => 10.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Mandarin Oranges
            [
                'product_id' => 13,
                'option' => 'Per box',
                'price' => 18.90,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Cabbages
            [
                'product_id' => 14,
                'option' => '400g',
                'price' => 3.70,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Pak Choy
            [
                'product_id' => 15,
                'option' => 'Per pack',
                'price' => 2.90,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Bitter Gourd
            [
                'product_id' => 16,
                'option' => '200g',
                'price' => 2.90,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Garlic
            [
                'product_id' => 17,
                'option' => '100g',
                'price' => 1.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 17,
                'option' => '200g',
                'price' => 2.80,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Red Chili Padi
            [
                'product_id' => 18,
                'option' => '50g',
                'price' => 1.20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 18,
                'option' => '100g',
                'price' => 2.30,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Green Chili Padi
            [
                'product_id' => 19,
                'option' => '50g',
                'price' => 1.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 19,
                'option' => '100g',
                'price' => 2.00,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Coriander
            [
                'product_id' => 20,
                'option' => '30g',
                'price' => 0.90,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 20,
                'option' => '50g',
                'price' => 1.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Turmeric
            [
                'product_id' => 21,
                'option' => '100g',
                'price' => 1.80,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 21,
                'option' => '200g',
                'price' => 3.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Cucumber
            [
                'product_id' => 22,
                'option' => '300g',
                'price' => 2.20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 22,
                'option' => '500g',
                'price' => 3.80,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Japanese Cucumber
            [
                'product_id' => 23,
                'option' => '300g',
                'price' => 2.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 23,
                'option' => '500g',
                'price' => 4.00,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Lemongrass
            [
                'product_id' => 24,
                'option' => '200g',
                'price' => 2.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 24,
                'option' => '300g',
                'price' => 3.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Pumpkin
            [
                'product_id' => 25,
                'option' => '1kg',
                'price' => 4.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 25,
                'option' => '2kg',
                'price' => 8.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Pineapple
            [
                'product_id' => 26,
                'option' => '1 whole',
                'price' => 5.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 26,
                'option' => 'Half',
                'price' => 3.00,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Lemon
            [
                'product_id' => 27,
                'option' => '3 pieces',
                'price' => 2.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 27,
                'option' => '1kg',
                'price' => 6.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Limau Nipis
            [
                'product_id' => 28,
                'option' => '200g',
                'price' => 1.80,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 28,
                'option' => '400g',
                'price' => 3.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Limau Kasturi
            [
                'product_id' => 29,
                'option' => '200g',
                'price' => 1.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 29,
                'option' => '400g',
                'price' => 2.80,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Watermelon
            [
                'product_id' => 30,
                'option' => '1 whole',
                'price' => 8.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 30,
                'option' => 'Half',
                'price' => 4.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Coriander Seed
            [
                'product_id' => 31,
                'option' => '100g',
                'price' => 2.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 31,
                'option' => '200g',
                'price' => 3.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Turmeric Powder
            [
                'product_id' => 32,
                'option' => '50g',
                'price' => 1.80,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 32,
                'option' => '100g',
                'price' => 3.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Paprika Powder
            [
                'product_id' => 33,
                'option' => '50g',
                'price' => 2.20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 33,
                'option' => '100g',
                'price' => 4.00,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Cinnamon
            [
                'product_id' => 34,
                'option' => '50g',
                'price' => 2.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 34,
                'option' => '100g',
                'price' => 4.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Cinnamon Powder
            [
                'product_id' => 35,
                'option' => '50g',
                'price' => 2.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 35,
                'option' => '100g',
                'price' => 3.80,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Mandarin Oranges (Clearance)
            [
                'product_id' => 36,
                'option' => 'Per box',
                'price' => 8.90,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Avocado (Clearance)
            [
                'product_id' => 37,
                'option' => '500g',
                'price' => 4.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Bitter Gourd (Clearance)
            [
                'product_id' => 38,
                'option' => '200g',
                'price' => 1.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Cabbages (Clearance)
            [
                'product_id' => 39,
                'option' => '200g',
                'price' => 1.70,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Pak Choy (Clearance)
            [
                'product_id' => 40,
                'option' => 'Per pack',
                'price' => 1.30,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Cavendish Banana
            [
                'product_id' => 41,
                'option' => 'Per bundle',
                'price' => 4.60,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Seedless Green Grapes
            [
                'product_id' => 42,
                'option' => '+/-800g',
                'price' => 13.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Seedless Red Grapes
            [
                'product_id' => 43,
                'option' => '+/-800g',
                'price' => 13.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Seedless Green Grapes
            [
                'product_id' => 44,
                'option' => '+/-800g',
                'price' => 7.50,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Seedless Red Grapes
            [
                'product_id' => 45,
                'option' => '+/-800g',
                'price' => 7.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Potato
            [
                'product_id' => 46,
                'option' => '500g',
                'price' => 1.70,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 46,
                'option' => '1kg',
                'price' => 3.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Russet Potato
            [
                'product_id' => 47,
                'option' => '500g',
                'price' => 2.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 47,
                'option' => '1kg',
                'price' => 4.40,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Papaya
            [
                'product_id' => 48,
                'option' => '1 whole',
                'price' => 5.20,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Papaya (Clearance)
            [
                'product_id' => 49,
                'option' => '1 whole',
                'price' => 3.20,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('product_options')->insert($productOptions);
    }
}