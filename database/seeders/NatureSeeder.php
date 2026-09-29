<?php

namespace Database\Seeders;

use App\Models\Nature;
use Illuminate\Database\Seeder;

class NatureSeeder extends Seeder
{
    public function run(): void
    {
        Nature::updateOrCreate(
            ['slug' => 'humano'],
            ['name' => 'Humano']
        );

        Nature::updateOrCreate(
            ['slug' => 'animal'],
            ['name' => 'Animal']
        );

        Nature::updateOrCreate(
            ['slug' => 'vegetal'],
            ['name' => 'Vegetal']
        );

        Nature::updateOrCreate(
            ['slug' => 'material'],
            ['name' => 'Material']
        );

        Nature::updateOrCreate(
            ['slug' => 'inmaterial'],
            ['name' => 'Inmaterial']
        );
    }
}
