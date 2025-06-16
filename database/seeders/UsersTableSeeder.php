<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'uid' => User::generateUniqueUid(),
                'name' => 'Serena Ng Yen Xin',
                'email' => 'yenxinng@gmail.com',
                'password' => Hash::make('password123'),
                'contact' => '01154325432',
                'remember_token' => Str::random(10),
            ],
            [
                'uid' => User::generateUniqueUid(),
                'name' => 'Customer User',
                'email' => 'customer@example.com',
                'password' => Hash::make('password'),
                'contact' => '01123452345',
                'remember_token' => Str::random(10),
            ],
        ];
        DB::table('users')->insert($users);
    }
}
