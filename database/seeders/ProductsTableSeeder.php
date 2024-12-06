<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'name' => 'Sample Vegetables', 
                'image' => 'img/vegetables/vegetables.png',
                'price' => 99.99, 
                'description' => 'This is just a sample.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name' => 'Broccoli', 
                'image' => 'img/vegetables/broccoli.jpg',
                'price' => 2.50, 
                'description' => 'Fresh broccoli description.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name' => 'Carrot', 
                'image' => 'img/vegetables/carrot.jpg',
                'price' => 1.90, 
                'description' => 'Carrots! Description here.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name' => 'Spring Onion', 
                'image' => 'img/vegetables/springonion.jpg',
                'price' => 1.20, 
                'description' => 'Spring onions for sale.', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name' => 'Shallot', 
                'image' => 'img/vegetables/shallot.webp',
                'price' => 1.50, 
                'description' => 'Shallots, anyone?', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name' => 'Ginger', 
                'image' => 'img/vegetables/ginger.jpg',
                'price' => 1.90, 
                'description' => 'GINGERRRRR.', 
                'created_at' => now(), 
                'updated_at' => now()
            ]
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
