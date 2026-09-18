<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Jose Fernando Riquelme Vergara',
            'email' => 'ingenieria@pepesky.cl',
            'password' => bcrypt('kudkug-4byWra-zobnar')
        ]);

        User::factory(10)->create();
    }
}
