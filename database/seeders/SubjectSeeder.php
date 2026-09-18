<?php

namespace Database\Seeders;

use App\Models\Modules\Subjects\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subject::create([
            'title' => 'Administración Negocios',
        ]);

        Subject::create([
            'title' => 'Contabilidad 2',
        ]);

        Subject::create([
            'title' => 'Técnología de la Información 1',
        ]);

        Subject::create([
            'title' => 'Derecho Empresarial',
        ]);
    }
}
