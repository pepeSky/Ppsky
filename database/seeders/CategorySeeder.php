<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Personal',
            'slug' => 'personal',
            'categoryable_id' => '1',
            'categoryable_type' => 'Models/Resource_Interaction/Business'
        ]);

        Category::create([
            'name' => 'Colectivo',
            'slug' => 'colectivo',
            'categoryable_id' => '2',
            'categoryable_type' => 'Models/Resource_Interaction/Business'
        ]);

        Category::create([
            'name' => 'Social',
            'slug' => 'social',
            'categoryable_id' => '3',
            'categoryable_type' => 'Models/Resource_Interaction/Business'
        ]);

        Category::create([
            'name' => 'Fiscal',
            'slug' => 'fiscal',
            'categoryable_id' => '4',
            'categoryable_type' => 'Models/Resource_Interaction/Business'
        ]);

        Category::create([
            'name' => 'Análisis',
            'slug' => 'analisis',
            'categoryable_id' => '1',
            'categoryable_type' => 'Models/Post'
        ]);

        Category::create([
            'name' => 'Preventiva',
            'slug' => 'preventiva',
            'categoryable_id' => '2',
            'categoryable_type' => 'Models/Post'
        ]);

        Category::create([
            'name' => 'Correctiva',
            'slug' => 'correctiva',
            'categoryable_id' => '3',
            'categoryable_type' => 'Models/Post'
        ]);

        Category::create([
            'name' => 'Emergencia',
            'slug' => 'emergencia',
            'categoryable_id' => '4',
            'categoryable_type' => 'Models/Post'
        ]);

        /** Entities */
        Category::create([
            'name' => 'Individuo',
            'slug' => 'individuo',
            'categoryable_id' => '1',
            'categoryable_type' => 'Models/Entity'
        ]);

        Category::create([
            'name' => 'Colectivo',
            'slug' => 'individuo',
            'categoryable_id' => '2',
            'categoryable_type' => 'Models/Entity'
        ]);

        Category::create([
            'name' => 'Comunidad',
            'slug' => 'comunidad',
            'categoryable_id' => '3',
            'categoryable_type' => 'Models/Entity'
        ]);

        Category::create([
            'name' => 'Empresa',
            'slug' => 'empresa',
            'categoryable_id' => '4',
            'categoryable_type' => 'Models/Entity'
        ]);

        Category::create([
            'name' => 'Institución',
            'slug' => 'institucion',
            'categoryable_id' => '5',
            'categoryable_type' => 'Models/Entity'
        ]);

        Category::create([
            'name' => 'Fiscal',
            'slug' => 'fiscal',
            'categoryable_id' => '6',
            'categoryable_type' => 'Models/Entity'
        ]);

    }
}
