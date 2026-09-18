<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create([
            'name' => 'Negocio',
            'symbol' => 'UnNe',
        ]);

        Unit::create([
            'name' => 'Servicio',
            'symbol' => 'UnSe',
        ]);

        Unit::create([
            'name' => 'Soporte',
            'symbol' => 'UnSo',
        ]);

        Unit::create([
            'name' => 'Operativa',
            'symbol' => 'UnOp',
        ]);

        Unit::create([
            'name' => 'Elemental Nivel 1',
            'symbol' => 'UnEl_Niv1',
        ]);

        Unit::create([
            'name' => 'Elemental Nivel 2',
            'symbol' => 'UnEl_Niv2',
        ]);

        Unit::create([
            'name' => 'Elemental Nivel 3',
            'symbol' => 'UnEl_Niv3',
        ]);
    }
}
