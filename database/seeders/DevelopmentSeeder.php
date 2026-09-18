<?php

namespace Database\Seeders;

use App\Models\Audience;
use App\Models\Description;
use App\Models\Development;
use App\Models\Goal;
use App\Models\Image;
use App\Models\Instruction;
use App\Models\Module;
use App\Models\Process;
use App\Models\Requirement;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Development::create([
            'title' => 'PepeSky',
            'subtitle' => 'Planificando un viaje a través del espacio',
            'description' => 'Desarrollo destinado a mejorar la calidad de vida de pepe dentro de su ciclo de vida',
            'status' => 1,
            'slug' => 'pepesky',
            'user_id' => 1,
            'level_id' => 1,
            'category_id' => 1,
            'price_id' => 2,
        ]);

        //** Development::create([
        //   'title' => 'Modelo Negocio',
        //  'subtitle' => 'Transformando Realidades',
        //    'description' => 'Desarrollo destinado a dar solución a los problemas de recursos, para la    gestión de actividades del entorno',
        //   'status' => 1,
        //   'slug' => 'modelo-negocio',
        //  'user_id' => 1,
        //'level_id' => 1,
        //'category_id' => 1,
        //'price_id' => 2,]);

        Development::create([
            'title' => 'Comunidad Bicentenario LordCochrane',
            'subtitle' => 'Mantengamos a Nuestra Comunidad Conectada y Activada',
            'description' => 'desarrollo destinado a dar solución a las problemáticas de la comunidad bicentenario de Lord Cochrane con un enfoque de mantenimiento',
            'status' => '1',
            'slug' => 'comunidad-bicentenario-lordcochrane',
            'user_id' => 1,
            'level_id' => 2,
            'category_id' => 1,
            'price_id' => 3,
        ]);

        Development::create([
            'title' => 'mantcic life - Manteniendo Ciclos de Vida',
            'subtitle' => 'Generando valor para el futuro, manteniendo el entorno y el presente',
            'description' => 'Desarrollo destinado a la solución de problemas a nivel simbiótico en la población con el foco de mantenimiento.',
            'status' => '1',
            'slug' => 'mantcic-life-manteniendo-ciclos-de-vida',
            'user_id' => 1,
            'level_id' => 1,
            'category_id' => 1,
            'price_id' => 2,
        ]);
        //

        $developments = Development::all();


        foreach($developments as $development){

                Requirement::factory(4)->create([
                    'development_id' => $development->id,
                ]);

                Audience::factory(4)->create([
                    'development_id' => $development->id,
                ]);

                Goal::factory(4)->create([
                    'development_id' => $development->id,
                ]);

                $modules = Module::factory(4)->create([
                    'development_id' => $development->id,
                ]);

                    foreach ($modules as $module) {
                       $processes = Process::factory(5)->create([
                            'module_id' => $module->id,
                        ]);
                    }
        }
    }
}
