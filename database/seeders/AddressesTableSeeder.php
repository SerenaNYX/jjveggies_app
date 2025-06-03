<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Address;
use App\Models\User;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example: Seed data for 10 users, each with 1-3 addresses
        $users = User::all();

        foreach ($users as $user) {
            // Each user gets 1-3 random addresses
            $addressCount = rand(1, 3);

            for ($i = 0; $i < $addressCount; $i++) {
                Address::create([
                    'user_id' => $user->id,
                    'address' => "123, Jalan Contoh 123, Taman Contoh, Johor Bahru",
                    'postal_code' => "81100",
                    'phone' => "01112345678",
                ]);
            }
        }
    }
    
}
