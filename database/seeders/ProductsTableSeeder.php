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
                'description' => 'Fresh broccoli description.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Carrot', 
                'slug' => Str::slug('Carrot'),
                'image' => 'img/vegetables/carrot.jpg',
                'price' => 1.90, 
                'description' => 'Carrots! Description here.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Spring Onion', 
                'slug' => Str::slug('Spring Onion'),
                'image' => 'img/vegetables/springonion.jpg',
                'price' => 1.20, 
                'description' => 'Spring onions for sale.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Shallot', 
                'slug' => Str::slug('Shallot'),
                'image' => 'img/vegetables/shallot.webp',
                'price' => 1.50, 
                'description' => 'Shallots, anyone?', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Ginger', 
                'slug' => Str::slug('Ginger'),
                'image' => 'img/vegetables/ginger.jpg',
                'price' => 1.90, 
                'description' => 'GINGERRRRR.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 2,
                'name' => 'Strawberry', 
                'slug' => Str::slug('Strawberry'),
                'image' => 'img/fruits/strawberry.jpg',
                'price' => 14.90, 
                'description' => 'Cameron Strawberries.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name' => 'Black Pepper', 
                'slug' => Str::slug('Black Pepper'),
                'image' => 'img/condiments/blackpepper.jpg',
                'price' => 10.90, 
                'description' => 'Dried black pepper.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name' => 'Ground Black Pepper', 
                'slug' => Str::slug('Ground Black Pepper'),
                'image' => 'img/condiments/groundblackpepper.jpg',
                'price' => 10.90, 
                'description' => 'Ground black pepper.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name' => 'White Pepper', 
                'slug' => Str::slug('White Pepper'),
                'image' => 'img/condiments/whitepepper.jpg',
                'price' => 10.90, 
                'description' => 'Dried white pepper.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name' => 'Ground White Pepper', 
                'slug' => Str::slug('Ground White Pepper'),
                'image' => 'img/condiments/groundwhitepepper.jpg',
                'price' => 10.90, 
                'description' => 'Ground white pepper.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 2,
                'name' => 'Avocado', 
                'slug' => Str::slug('Avocado'),
                'image' => 'img/fruits/avocado.jpg',
                'price' => 10.50,
                'description' => '', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 2,
                'name' => 'Mandarin Oranges',
                'slug' => Str::slug('Mandarin Oranges'),
                'image' => 'img/fruits/mandarinorange.jpg',
                'price' => 9.90,
                'description' => '', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Cabbages',
                'slug' => Str::slug('Cabbages'),
                'image' => 'img/vegetables/cabbage.jpg',
                'price' => 3.70,
                'description' => '', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Pak Choy',
                'slug' => Str::slug('Pak Choy'),
                'image' => 'img/vegetables/pakchoy.jpg',
                'price' => 2.90,
                'description' => '', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Bitter Gourd',
                'slug' => Str::slug('Bitter Gourd'),
                'image' => 'img/vegetables/bittergours.jpg',
                'price' => 2.90,
                'description' => '', 
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
