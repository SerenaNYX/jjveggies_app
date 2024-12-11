<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Serena',
                'email' => 'serena@example.com',
                'password' => Hash::make('password'),
                'contact' => '01123456789',
                'address' => '123, My House',
                'remember_token' => Str::random(10),
            ],
            // Add more users as needed
        ];

        DB::table('users')->insert($users);
    }
}
