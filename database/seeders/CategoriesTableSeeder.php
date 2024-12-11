<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Vegetables', 'slug' => Str::slug('Vegetables')],
            ['name' => 'Fruits', 'slug' => Str::slug('Fruits')],
            ['name' => 'Condiments', 'slug' => Str::slug('Condiments')],
            ['name' => 'Clearance', 'slug' => Str::slug('Clearance')],
        ];

        DB::table('categories')->insert($categories);
    }
}
