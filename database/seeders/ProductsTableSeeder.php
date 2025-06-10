<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    protected function generateUniqueProductNumber($characters)
    {
        do {
            $productNumber = '';
            for ($i = 0; $i < 4; $i++) {
                $productNumber .= $characters[rand(0, strlen($characters) - 1)];
            }
            $exists = DB::table('products')->where('product_number', $productNumber)->exists();
        } while ($exists);
        
        return $productNumber;
    }
    public function run()
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        // TO ADD: 
        // VEGETABLES: garlic.webp, redchilipadi.webp, greenchilipadi.jpg, coriander.webp, turmeric.webp, cucumber.jpg, japanesecucumber.png, lemongrass.jpg, pumpkin.jpg
        // FRUITS: pineapple.jpg, lemon.jpg, limaunipis.webp, limaukasturi.png, watermelon.jpg
        // CONDIMENTS: corianderseed.jpg, turmericpowder.webp, paprikapowder.jpg, cinnamon.webp, cinnamonpowder.jpg
        $products = [
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Sample Vegetables',
                'slug' => Str::slug('Sample Vegetables'),
                'image' => 'img/vegetables/vegetables.png',
                'description' => 'This is just a sample.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Broccoli',
                'slug' => Str::slug('Broccoli'),
                'image' => 'img/vegetables/broccoli.jpg',
                'description' => 'Fresh broccoli.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Carrot',
                'slug' => Str::slug('Carrot'),
                'image' => 'img/vegetables/carrot.jpg',
                'description' => 'Fresh carrots.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Spring Onion',
                'slug' => Str::slug('Spring Onion'),
                'image' => 'img/vegetables/springonion.jpg',
                'description' => 'Fresh spring onions.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Shallot',
                'slug' => Str::slug('Shallot'),
                'image' => 'img/vegetables/shallot.webp',
                'description' => 'Fresh shallots.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Ginger',
                'slug' => Str::slug('Ginger'),
                'image' => 'img/vegetables/ginger.jpg',
                'description' => 'Fresh ginger.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 2,
                'name' => 'Strawberry',
                'slug' => Str::slug('Strawberry'),
                'image' => 'img/fruits/strawberry.jpg',
                'description' => 'Fresh strawberries.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 3,
                'name' => 'Black Pepper',
                'slug' => Str::slug('Black Pepper'),
                'image' => 'img/condiments/blackpepper.jpg',
                'description' => 'Whole black pepper.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 3,
                'name' => 'Ground Black Pepper',
                'slug' => Str::slug('Ground Black Pepper'),
                'image' => 'img/condiments/groundblackpepper.jpg',
                'description' => 'Ground black pepper.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 3,
                'name' => 'White Pepper',
                'slug' => Str::slug('White Pepper'),
                'image' => 'img/condiments/whitepepper.jpg',
                'description' => 'Whole white pepper.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 3,
                'name' => 'Ground White Pepper',
                'slug' => Str::slug('Ground White Pepper'),
                'image' => 'img/condiments/groundwhitepepper.jpg',
                'description' => 'Ground white pepper.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 2,
                'name' => 'Avocado',
                'slug' => Str::slug('Avocado'),
                'image' => 'img/fruits/avocado.jpg',
                'description' => 'Fresh avocados.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 2,
                'name' => 'Mandarin Oranges',
                'slug' => Str::slug('Mandarin Oranges'),
                'image' => 'img/fruits/mandarinorange.jpg',
                'description' => 'Fresh mandarin oranges.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Cabbages',
                'slug' => Str::slug('Cabbages'),
                'image' => 'img/vegetables/cabbage.jpg',
                'description' => 'Fresh cabbages.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Pak Choy',
                'slug' => Str::slug('Pak Choy'),
                'image' => 'img/vegetables/pakchoy.jpg',
                'description' => 'Fresh pak choy.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Bitter Gourd',
                'slug' => Str::slug('Bitter Gourd'),
                'image' => 'img/vegetables/bittergourd.jpg',
                'description' => 'Fresh bitter gourd.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Garlic',
                'slug' => Str::slug('Garlic'),
                'image' => 'img/vegetables/garlic.webp',
                'description' => 'Fresh garlic.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Red Chili Padi',
                'slug' => Str::slug('Red Chili Padi'),
                'image' => 'img/vegetables/redchilipadi.webp',
                'description' => 'Fresh red chili padi.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Green Chili Padi',
                'slug' => Str::slug('Green Chili Padi'),
                'image' => 'img/vegetables/greenchilipadi.jpg',
                'description' => 'Fresh green chili padi.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Coriander',
                'slug' => Str::slug('Coriander'),
                'image' => 'img/vegetables/coriander.webp',
                'description' => 'Fresh coriander.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Turmeric',
                'slug' => Str::slug('Turmeric'),
                'image' => 'img/vegetables/turmeric.webp',
                'description' => 'Fresh turmeric.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Cucumber',
                'slug' => Str::slug('Cucumber'),
                'image' => 'img/vegetables/cucumber.jpg',
                'description' => 'Fresh cucumber.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Japanese Cucumber',
                'slug' => Str::slug('Japanese Cucumber'),
                'image' => 'img/vegetables/japanesecucumber.png',
                'description' => 'Fresh Japanese cucumber.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Lemongrass',
                'slug' => Str::slug('Lemongrass'),
                'image' => 'img/vegetables/lemongrass.jpg',
                'description' => 'Fresh lemongrass.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Pumpkin',
                'slug' => Str::slug('Pumpkin'),
                'image' => 'img/vegetables/pumpkin.jpg',
                'description' => 'Fresh pumpkin.',
                'created_at' => now(),
                'updated_at' => now()
            ],      
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 2,
                'name' => 'Pineapple',
                'slug' => Str::slug('Pineapple'),
                'image' => 'img/fruits/pineapple.jpg',
                'description' => 'Fresh pineapple.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 2,
                'name' => 'Lemon',
                'slug' => Str::slug('Lemon'),
                'image' => 'img/fruits/lemon.jpg',
                'description' => 'Fresh lemon.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 2,
                'name' => 'Limau Nipis',
                'slug' => Str::slug('Limau Nipis'),
                'image' => 'img/fruits/limaunipis.webp',
                'description' => 'Fresh limau nipis.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 2,
                'name' => 'Limau Kasturi',
                'slug' => Str::slug('Limau Kasturi'),
                'image' => 'img/fruits/limaukasturi.png',
                'description' => 'Fresh limau kasturi.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 2,
                'name' => 'Watermelon',
                'slug' => Str::slug('Watermelon'),
                'image' => 'img/fruits/watermelon.jpg',
                'description' => 'Fresh watermelon.',
                'created_at' => now(),
                'updated_at' => now()
            ],  
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 3,
                'name' => 'Coriander Seed',
                'slug' => Str::slug('Coriander Seed'),
                'image' => 'img/condiments/corianderseed.jpg',
                'description' => 'Whole coriander seeds.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 3,
                'name' => 'Turmeric Powder',
                'slug' => Str::slug('Turmeric Powder'),
                'image' => 'img/condiments/turmericpowder.webp',
                'description' => 'Ground turmeric powder.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 3,
                'name' => 'Paprika Powder',
                'slug' => Str::slug('Paprika Powder'),
                'image' => 'img/condiments/paprikapowder.jpg',
                'description' => 'Ground paprika powder.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 3,
                'name' => 'Cinnamon',
                'slug' => Str::slug('Cinnamon'),
                'image' => 'img/condiments/cinnamon.webp',
                'description' => 'Whole cinnamon sticks.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 3,
                'name' => 'Cinnamon Powder',
                'slug' => Str::slug('Cinnamon Powder'),
                'image' => 'img/condiments/cinnamonpowder.jpg',
                'description' => 'Ground cinnamon powder.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Clearance Products
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 4,
                'name' => 'Mandarin Oranges (Clearance)',
                'slug' => Str::slug('Mandarin Oranges (Clearance)'),
                'image' => 'img/fruits/mandarinorange.jpg',
                'description' => 'Clearance mandarin oranges.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 4,
                'name' => 'Avocado (Clearance)',
                'slug' => Str::slug('Avocado (Clearance)'),
                'image' => 'img/fruits/avocado.jpg',
                'description' => 'Clearance avocados.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 4,
                'name' => 'Bitter Gourd (Clearance)',
                'slug' => Str::slug('Bitter Gourd (Clearance)'),
                'image' => 'img/vegetables/bittergourd.jpg',
                'description' => 'Clearance bitter gourd.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 4,
                'name' => 'Cabbages (Clearance)',
                'slug' => Str::slug('Cabbages (Clearance)'),
                'image' => 'img/vegetables/cabbage.jpg',
                'description' => 'Clearance cabbages.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 4,
                'name' => 'Pak Choy (Clearance)',
                'slug' => Str::slug('Pak Choy (Clearance)'),
                'image' => 'img/vegetables/pakchoy.jpg',
                'description' => 'Clearance pak choy.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 2,
                'name' => 'Cavendish Banana',
                'slug' => Str::slug('Cavendish Banana'),
                'image' => 'img/fruits/cavendishbanana.jpg',
                'description' => 'Fresh bananas.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 2,
                'name' => 'Seedless Green Grapes',
                'slug' => Str::slug('Seedless Green Grapes'),
                'image' => 'img/fruits/greenseedlessgrapes.webp',
                'description' => 'Seedless grapes.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 2,
                'name' => 'Seedless Red Grapes',
                'slug' => Str::slug('Seedless Red Grapes'),
                'image' => 'img/fruits/redseedlessgrapes.jpg',
                'description' => 'Clearance seedless grapes.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 4,
                'name' => 'Seedless Green Grapes (Clearance)',
                'slug' => Str::slug('Seedless Green Grapes (Clearance)'),
                'image' => 'img/fruits/greenseedlessgrapes.webp',
                'description' => 'Clearance seedless grapes.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 4,
                'name' => 'Seedless Red Grapes (Clearance)',
                'slug' => Str::slug('Seedless Red Grapes (Clearance)'),
                'image' => 'img/fruits/redseedlessgrapes.jpg',
                'description' => 'Seedless grapes.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Potato',
                'slug' => Str::slug('Potato'),
                'image' => 'img/vegetables/potato.jpg',
                'description' => 'Potato.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 1,
                'name' => 'Russet Potato',
                'slug' => Str::slug('Russet Potato'),
                'image' => 'img/vegetables/russetpotato.webp',
                'description' => 'Russet potato.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 2,
                'name' => 'Papaya',
                'slug' => Str::slug('Papaya'),
                'image' => 'img/fruits/papaya.jpg',
                'description' => 'Fresh papaya.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_number' => $this->generateUniqueProductNumber($characters),
                'category_id' => 4,
                'name' => 'Papaya (Clearance)',
                'slug' => Str::slug('Papaya (Clearance)'),
                'image' => 'img/fruits/papaya.jpg',
                'description' => 'Clearance stock.',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('products')->insert($products);
    }
}