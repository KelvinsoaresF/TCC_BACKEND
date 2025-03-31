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
            'phone' => '12345678',
            'cep' => '1222222222'
        ],
        // [
        //     'name' => 'user1',
        //     'email' => 'user1@example.com',
        //     'password' => Hash::make('password123'),
        //     'phone' => '12345678',
        //     'cep' => '1222222222'
        // ]
        );
    }
}
