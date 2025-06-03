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
                'name' => 'Serena',
                'email' => 'yenxinng@gmail.com',
                'password' => Hash::make('password'),
                'contact' => '0115479225',
                'remember_token' => Str::random(10),
            ],
            [
                'uid' => User::generateUniqueUid(),
                'name' => 'Saera',
                'email' => 'saera@example.com',
                'password' => Hash::make('password'),
                'contact' => '0174215779',
                'remember_token' => Str::random(10),
            ],
        ];
        // teolilynatasha@gmail.com
        DB::table('users')->insert($users);
    }
}
