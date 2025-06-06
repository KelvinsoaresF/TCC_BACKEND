<?php

namespace Database\Seeders;

use App\Models\AnimalPost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnimalPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AnimalPost::create(
            [
            'user_id' => 1,
            'title' => 'Cachorro perdido',
            'description' => 'Cachorro encontrado na rua, parece estar perdido.',
            'category' => 'Cachorro',
            'cep' => '12345-678',
            'state' => 'SP',
            'city' => 'São Paulo',
            'contact' => '11987654321',
            'status' => 'Disponível',
            'sex' => 'Macho',
            'age' => 23,
            'posted_at' => now(),
            ],
            [
                'user_id' => 2,
                'title' => 'Gato perdido',
                'description' => 'Gato encontrado na rua, parece estar perdido.',
                'category' => 'Gato',
                'cep' => '12345-678',
                'state' => 'SP',
                'city' => 'São Paulo',
                'contact' => '11987654321',
                'status' => 'Disponível',
                'sex' => 'Macho',
                'age' => 4,
                'posted_at' => now(),
            ]
    );
    }
}
