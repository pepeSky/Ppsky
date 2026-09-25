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
        // Conservar el ID de Negocio cuando proviene del cargador anterior.
        if (! Unit::where('symbol', 'UnNE')->exists()) {
            Unit::where('symbol', 'UnNe')->update(['symbol' => 'UnNE']);
        }

        foreach ([
            'UnNE' => 'Negocio',
            'UnSe' => 'Servicio',
            'UnSo' => 'Soporte',
            'UnOp' => 'Operativa',
            'UnEl' => 'Elemental',
        ] as $symbol => $name) {
            Unit::updateOrCreate(['symbol' => $symbol], ['name' => $name]);
        }
    }
}
