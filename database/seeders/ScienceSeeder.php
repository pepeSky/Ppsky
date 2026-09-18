<?php

namespace Database\Seeders;

use App\Models\Modules\Subjects\Science;
use Illuminate\Database\Seeder;

class ScienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Science::create([
            'name' => 'Exactas e Ingeniería'
        ]);

        Science::create([
            'name' => 'Económicas y Administrativas'
        ]);

        Science::create([
            'name' => 'De la Salud'
        ]);

        Science::create([
            'name' => 'Biológicas y Agricultura'
        ]);

        Science::create([
            'name' => 'Humanas y Sociales'
        ]);



    }
}
