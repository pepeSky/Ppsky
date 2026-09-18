<?php

namespace Database\Seeders;

use App\Models\Modules\Subjects\Contents;
use Illuminate\Database\Seeder;

class ContentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Contents::create([
            'contents' => 'Economía y Negocios',
            'subject_id' => '1',
        ]);

        Contents::create([
            'contents' => 'Las Organizaciones Actuales y Futuras',
            'subject_id' => '1',
        ]);

        Contents::create([
            'contents' => 'Administración de Empresas',
            'subject_id' => '1',
        ]);

        Contents::create([
            'contents' => 'Etica y Sostenibilidad para el Siglo XXI',
            'subject_id' => '1',
        ]);
    }
}
