<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::create([
            'name' => 'Cuidado Abuelita Nives',
            'slug' => 'cuidado-abuelita-nieves',
            'extract' => 'Realizar cuidados personales a abuelita',
            'body' => 'registro de actividades abuelita nieves',
            'status' => '1',
            'module_id' => '7', //Clínico
            'category_id' => '2',
        ]);

        Post::create([
            'name' => 'Cuidado Abuelita Olivia',
            'slug' => 'cuidado-abuelita-olivia',
            'extract' => 'Realizar cuidados personales a abuelita',
            'body' => 'regsitro de actividades a buelita olivia',
            'status' => '1',
            'module_id' => '7', //Clínico
            'category_id' => '2'
        ]);

        Post::create([
            'name' => 'Mantenimiento Bicentenario',
            'slug' => 'mantenimiento-bicentenario',
            'extract' => 'Toma de solicitudes, para la planificación de actividades',
            'body' => 'registro de solicitudes bicentenario',
            'status' => '1',
            'module_id' => '4', //Mantenimiento
            'category_id' => '2'
        ]);

        Post::create([
            'name' => 'Mantenimiento El Cerro',
            'slug' => 'mantenimiento-el-cerro',
            'extract' => 'Toma de solicitudes, para la planificación de actividades',
            'body' => 'se establecieron los mecanismos de control de la comunicad',
            'status' => '1',
            'module_id' => '4', //Mantenimiento
            'category_id' => '3'
        ]);

        Post::create([
            'name' => 'Administración 1',
            'slug' => 'administracion-1',
            'extract' => 'Asignatura destinada a aprender los conceptos de la admistración',
            'body' => 'sera una administración de excelencia',
            'status' => '1',
            'module_id' => '8', //Studies
            'category_id' => '1'
        ]);

        Post::create([
            'name' => 'pepeSky Ingenieria',
            'slug' => 'pepesky-ingenieria',
            'extract' => 'Contabilizando a nivel cuantico, proyectos y desarrollos',
            'body' => 'bitacora destinada a intervenir en la solicitud de la causa',
            'status' => '1',
            'module_id' => '1', //Desarrollo
            'category_id' => '2'
        ]);

        Post::create([
            'name' => 'manTcic Life Mantenimiento',
            'slug' => 'mantcic-life-mantenimiento',
            'extract' => 'deteniendo el tiempo con el mantenimiento',
            'body' => 'bitacora destinada a intervenir en la solicitud de la causa',
            'status' => '1',
            'module_id' => '1', //Desarrollo
            'category_id' => '2'
        ]);

        Post::create([
            'name' => 'Valosevic Constructor',
            'slug' => 'valosevic-constructor',
            'extract' => 'contruyendo un imperio',
            'body' => 'forzando la mano de los mas debiles, solo conseguiras la ira de aquellos que luchann por la justicia',
            'status' => '1',
            'module_id' => '1', //Desarrollo
            'category_id' => '2'
        ]);

    }
}
