<?php

namespace Database\Seeders;

use App\Models\Modules\Subjects\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sector::create([
            'name' => 'Mantenimiento',
            'branch_id' => '1'
        ]);

        Sector::create([
            'name' => 'Administración',
            'branch_id' => '1'
        ]);


    }
}
