<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::create([
            'type' => '',  // Correctiva, Preventiva, Emergencia, Analisis Condición
            'task' => '', // Hecho a realizar verbo
            'category' => '', // Inpeccionar, Lubricar, Ajustar, Reemplazar, Reparar, Ordenar,Limpiar,Clasificar, Estandarizar, Mantener.
            'module' => '',// Personal, Administración, Contabilidad, Mantenimiento, Ventas, Desarrollo, Diseño, Clinico, Almacen.
            'estado' => '', // Pendiente, En Proceso, Finalizada
        ]);
    }
}
