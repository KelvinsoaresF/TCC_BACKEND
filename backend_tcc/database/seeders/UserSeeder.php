<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'user1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
            'phone' => '19 93456-8978',

        ],
        // [
        //     'name' => 'user2',
        //     'email' => 'user2@example.com',
        //     'password' => Hash::make('password123'),
        //     'phone' => '123462980462935',
        // ]
        );
    }
}
