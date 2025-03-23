<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'Sample Vegetables',
                'slug' => Str::slug('Sample Vegetables'),
                'image' => 'img/vegetables/vegetables.png',
                'description' => 'This is just a sample.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Broccoli',
                'slug' => Str::slug('Broccoli'),
                'image' => 'img/vegetables/broccoli.jpg',
                'description' => 'Fresh broccoli.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Carrot',
                'slug' => Str::slug('Carrot'),
                'image' => 'img/vegetables/carrot.jpg',
                'description' => 'Fresh carrots.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Spring Onion',
                'slug' => Str::slug('Spring Onion'),
                'image' => 'img/vegetables/springonion.jpg',
                'description' => 'Fresh spring onions.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Shallot',
                'slug' => Str::slug('Shallot'),
                'image' => 'img/vegetables/shallot.webp',
                'description' => 'Fresh shallots.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Ginger',
                'slug' => Str::slug('Ginger'),
                'image' => 'img/vegetables/ginger.jpg',
                'description' => 'Fresh ginger.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 2,
                'name' => 'Strawberry',
                'slug' => Str::slug('Strawberry'),
                'image' => 'img/fruits/strawberry.jpg',
                'description' => 'Fresh strawberries.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name' => 'Black Pepper',
                'slug' => Str::slug('Black Pepper'),
                'image' => 'img/condiments/blackpepper.jpg',
                'description' => 'Whole black pepper.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name' => 'Ground Black Pepper',
                'slug' => Str::slug('Ground Black Pepper'),
                'image' => 'img/condiments/groundblackpepper.jpg',
                'description' => 'Ground black pepper.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name' => 'White Pepper',
                'slug' => Str::slug('White Pepper'),
                'image' => 'img/condiments/whitepepper.jpg',
                'description' => 'Whole white pepper.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 3,
                'name' => 'Ground White Pepper',
                'slug' => Str::slug('Ground White Pepper'),
                'image' => 'img/condiments/groundwhitepepper.jpg',
                'description' => 'Ground white pepper.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 2,
                'name' => 'Avocado',
                'slug' => Str::slug('Avocado'),
                'image' => 'img/fruits/avocado.jpg',
                'description' => 'Fresh avocados.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 2,
                'name' => 'Mandarin Oranges',
                'slug' => Str::slug('Mandarin Oranges'),
                'image' => 'img/fruits/mandarinorange.jpg',
                'description' => 'Fresh mandarin oranges.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Cabbages',
                'slug' => Str::slug('Cabbages'),
                'image' => 'img/vegetables/cabbage.jpg',
                'description' => 'Fresh cabbages.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Pak Choy',
                'slug' => Str::slug('Pak Choy'),
                'image' => 'img/vegetables/pakchoy.jpg',
                'description' => 'Fresh pak choy.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 1,
                'name' => 'Bitter Gourd',
                'slug' => Str::slug('Bitter Gourd'),
                'image' => 'img/vegetables/bittergourd.jpg',
                'description' => 'Fresh bitter gourd.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Clearance Products
            [
                'category_id' => 4,
                'name' => 'Mandarin Oranges (Clearance)',
                'slug' => Str::slug('Mandarin Oranges (Clearance)'),
                'image' => 'img/fruits/mandarinorange.jpg',
                'description' => 'Clearance mandarin oranges.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 4,
                'name' => 'Avocado (Clearance)',
                'slug' => Str::slug('Avocado (Clearance)'),
                'image' => 'img/fruits/avocado.jpg',
                'description' => 'Clearance avocados.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 4,
                'name' => 'Bitter Gourd (Clearance)',
                'slug' => Str::slug('Bitter Gourd (Clearance)'),
                'image' => 'img/vegetables/bittergourd.jpg',
                'description' => 'Clearance bitter gourd.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 4,
                'name' => 'Cabbages (Clearance)',
                'slug' => Str::slug('Cabbages (Clearance)'),
                'image' => 'img/vegetables/cabbage.jpg',
                'description' => 'Clearance cabbages.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'category_id' => 4,
                'name' => 'Pak Choy (Clearance)',
                'slug' => Str::slug('Pak Choy (Clearance)'),
                'image' => 'img/vegetables/pakchoy.jpg',
                'description' => 'Clearance pak choy.',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('products')->insert($products);
    }
}