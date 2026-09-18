<?php

namespace Database\Seeders;

use App\Models\Modules\Subjects\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([
            'name' => 'engenniering',
            'science_id' => '1'
        ]);
    }
}
