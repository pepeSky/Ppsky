<?php

namespace Database\Seeders;

use App\Models\Price;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Price::create([
            'name' => 'Gratis',
            'value' => '0'
        ]);

        Price::create([
            'name' => ' 1.00 UF$CLP Personal',
            'value' => '1'
        ]);

        Price::create([
            'name' => ' 5.00 UF$CLP Colectivo',
            'value' => '5'
        ]);

        Price::create([
            'name' => ' 8.00 UF$CLP Social',
            'value' => '8'
        ]);

        Price::create([
            'name' => ' 10.00 UF$CLP Fiscal',
            'value' => '10'
        ]);
    }
}
