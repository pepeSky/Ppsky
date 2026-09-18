<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::create([
            'module' => 'Personal',
            'objetive'=> 'El modulo personal tiene por finalidad dar cumplimiento a todas las gestiones de indoles personal, para que el usuario se potencie como entidad de valor.',
        ]);

        Module::create([
            'module' => 'Desarrollo',
            'objetive'=> 'El modulo desarrollo tiene por finalidad contextualizar todas las ideas y proyectos que el usuario desee concretar en algun momento.',
        ]);

        Module::create([
            'module' => 'Administración',
            'objetive'=> 'El modulo administración tiene por finalidad concretar las ideas y proyectos que el usuario necesite materializar.',
        ]);

        Module::create([
            'module' => 'Contabilidad',
            'objetive'=> 'El modulo contabilidad tiene por finalidad cuantificar los procesos a traves de hechos economicos y establecer los estados financieros que el usuario necesite acreditar',
        ]);

        Module::create([
            'module' => 'Mantenimiento',
            'objetive'=> 'El modulo mantenimiento tiene por finalidad generar una traza de las actividades y su proceso de ejecucion.',
        ]);

        Module::create([
            'module' => 'Venta',
            'objetive'=> 'El modulo Venta tiene por finalidad exponer las capañas de manera comercial para generar ingresos para la organización.',
        ]);

        Module::create([
            'module' => 'Almacen',
            'objetive'=> 'El modulo Almacen tiene por finalidad controlar las existencias de la organización para la ejecución de los proyectos.',
        ]);

        Module::create([
            'module' => 'Clínico',
            'objetive'=> 'El modulo clinico tiene por finalidad controlar los estados de saludos del usuario para alargar su ciclo de vida.',
        ]);

        Module::create([
            'module' => 'Estudio',
            'objetive'=> 'El modulo estudio tiene por finalidad registrar el conocimiento del indivividuo o de la organizacion obtenido a priori y su sintesis, para el desarrollo y control de actividades.',
        ]);

        Module::create([
            'module' => 'Diseño',
            'objetive'=> 'El modulo Diseño tiene por finalidad registrar el conocimiento del indivividuo o de la organizacion obtenido a priori y su sintesis, para el desarrollo y control de actividades.',
        ]);

    }
}
