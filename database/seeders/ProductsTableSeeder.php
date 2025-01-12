<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     public function run() {


        $products = [
            [
                'category_id' => 1,
                'name' => 'Sample Vegetables', 
                'slug' => Str::slug('Sample Vegetables'),
                'image' => 'img/vegetables/vegetables.png',
                'price' => 99.99, 
                'quantity' => 99,
                'description' => 'This is just a sample.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Broccoli', 
                'slug' => Str::slug('Brocolli'),
                'image' => 'img/vegetables/broccoli.jpg',
                'price' => 2.50, 
                'quantity' => 50,
                'description' => 'Per pcs', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Carrot', 
                'slug' => Str::slug('Carrot'),
                'image' => 'img/vegetables/carrot.jpg',
                'price' => 1.90, 
                'quantity' => 97,
                'description' => 'Per 300g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Spring Onion', 
                'slug' => Str::slug('Spring Onion'),
                'image' => 'img/vegetables/springonion.jpg',
                'price' => 1.20, 
                'quantity' => 60,
                'description' => 'Per 200g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Shallot', 
                'slug' => Str::slug('Shallot'),
                'image' => 'img/vegetables/shallot.webp',
                'price' => 1.50, 
                'quantity' => 57,
                'description' => 'Per 200g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Ginger', 
                'slug' => Str::slug('Ginger'),
                'image' => 'img/vegetables/ginger.jpg',
                'price' => 1.90, 
                'quantity' => 87,
                'description' => 'Per 200g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 2,
                'name' => 'Strawberry', 
                'slug' => Str::slug('Strawberry'),
                'image' => 'img/fruits/strawberry.jpg',
                'price' => 14.90, 
                'quantity' => 56,
                'description' => 'Per pack', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name' => 'Black Pepper', 
                'slug' => Str::slug('Black Pepper'),
                'image' => 'img/condiments/blackpepper.jpg',
                'price' => 10.90, 
                'quantity' => 68,
                'description' => 'Per 400g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name' => 'Ground Black Pepper', 
                'slug' => Str::slug('Ground Black Pepper'),
                'image' => 'img/condiments/groundblackpepper.jpg',
                'price' => 10.90, 
                'quantity' => 64,
                'description' => 'Per 400g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name' => 'White Pepper', 
                'slug' => Str::slug('White Pepper'),
                'image' => 'img/condiments/whitepepper.jpg',
                'price' => 10.90, 
                'quantity' => 57,
                'description' => 'Per 400g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name' => 'Ground White Pepper', 
                'slug' => Str::slug('Ground White Pepper'),
                'image' => 'img/condiments/groundwhitepepper.jpg',
                'price' => 10.90, 
                'quantity' => 74,
                'description' => 'Per 400g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 2,
                'name' => 'Avocado', 
                'slug' => Str::slug('Avocado'),
                'image' => 'img/fruits/avocado.jpg',
                'price' => 10.50,
                'quantity' => 39,
                'description' => 'Per 500g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 2,
                'name' => 'Mandarin Oranges',
                'slug' => Str::slug('Mandarin Oranges'),
                'image' => 'img/fruits/mandarinorange.jpg',
                'price' => 18.90,
                'quantity' => 99,
                'description' => 'Per box', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Cabbages',
                'slug' => Str::slug('Cabbages'),
                'image' => 'img/vegetables/cabbage.jpg',
                'price' => 3.70,
                'quantity' => 103,
                'description' => 'Per 400g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Pak Choy',
                'slug' => Str::slug('Pak Choy'),
                'image' => 'img/vegetables/pakchoy.jpg',
                'price' => 2.90,
                'quantity' => 93,
                'description' => 'Per pack', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Bitter Gourd',
                'slug' => Str::slug('Bitter Gourd'),
                'image' => 'img/vegetables/bittergourd.jpg',
                'price' => 2.90,
                'quantity' => 37,
                'description' => 'Per 200g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],

            //CLEARANCE
            [
                'category_id' => 4,
                'name' => 'Mandarin Oranges (Clearance)',
                'slug' => Str::slug('Mandarin Oranges (Clearance)'),
                'image' => 'img/fruits/mandarinorange.jpg',
                'price' => 8.90,
                'quantity' => 19,
                'description' => 'Per box', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 4,
                'name' => 'Avocado (Clearance)', 
                'slug' => Str::slug('Avocado (Clearance)'),
                'image' => 'img/fruits/avocado.jpg',
                'price' => 4.50,
                'quantity' => 9,
                'description' => 'Per 500g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 4,
                'name' => 'Bitter Gourd (Clearance)',
                'slug' => Str::slug('Bitter Gourd (Clearance)'),
                'image' => 'img/vegetables/bittergourd.jpg',
                'price' => 1.50,
                'quantity' => 7,
                'description' => '', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 4,
                'name' => 'Cabbages (Clearance)',
                'slug' => Str::slug('Cabbages (Clearance)'),
                'image' => 'img/vegetables/cabbage.jpg',
                'price' => 1.70,
                'quantity' => 23,
                'description' => 'Per 200g', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 4,
                'name' => 'Pak Choy (Clearance)',
                'slug' => Str::slug('Pak Choy (Clearance)'),
                'image' => 'img/vegetables/pakchoy.jpg',
                'price' => 1.30,
                'quantity' => 12,
                'description' => 'Per pack', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            

        ];

        DB::table('products')->insert($products);
    }


 /*   public function run()
    {
        DB::table('products')->truncate(); // Clear the table before seeding

        $vegetables = [
            ['Broccoli', 'broccoli.jpg', 2.50],
            ['Carrot', 'carrot.jpg', 3.00]
        ];

        foreach ($vegetables as $index => $veg) {
            Product::create([
                'product_name' => $veg[0],
                'product_price' => $veg[2],
                'image' => 'products/vegetables/' . $veg[1],
                'user_id' => 1, // Assuming the user ID is 1 for the seeding purpose
            ]);
        }
    } */
}
