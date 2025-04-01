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
                'contact' => '0115479225',
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Saera',
                'email' => 'saera@example.com',
                'password' => Hash::make('password'),
                'contact' => '0174215779',
                'remember_token' => Str::random(10),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
