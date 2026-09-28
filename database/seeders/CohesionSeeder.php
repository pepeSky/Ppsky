<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CohesionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cohesions = [
            [
                'name' => 'Existencial',
                'symbol' => '0:0',
                'description' => 'Cohesión asociada a actividades e interacciones de carácter existencial.',
            ],
            [
                'name' => 'Ocio',
                'symbol' => '0:1',
                'description' => 'Cohesión asociada a actividades e interacciones de ocio.',
            ],
            [
                'name' => 'Negocio',
                'symbol' => '1:1',
                'description' => 'Cohesión asociada a actividades e interacciones de negocio.',
            ],
        ];

        foreach ($cohesions as $cohesion) {
            \App\Models\Cohesion::updateOrCreate(
                ['name' => $cohesion['name']],
                $cohesion
            );
        }
    }
}
