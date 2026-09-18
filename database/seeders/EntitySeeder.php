<?php

namespace Database\Seeders;

use App\Models\Entity;
use Illuminate\Database\Seeder;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**Individuos */ {
            Entity::create([
                'name' => 'Jose Riquelme Vergara',
                'category_id' => '1',
                'code' => '0.001.',
            ]);

            Entity::create([
                'name' => 'Paulina Garay Santander',
                'category_id' => '1',
                'code' => '0.002.',
            ]);

            Entity::create([
                'name' => 'Andres Isaias Sanchez',
                'category_id' => '1',
                'code' => '0.003.',
            ]);

            Entity::create([
                'name' => 'Maria Cecilia Hernández Castro',
                'category_id' => '1',
                'code' => '0.004.',
            ]);

            Entity::create([
                'name' => 'Adela del Carmen Duran Opazo',
                'category_id' => '1',
                'code' => '0.005.',
            ]);

            Entity::create([
                'name' => 'Luis Osvaldo Moya Abarca',
                'category_id' => '1',
                'code' => '0.006.',
            ]);

            Entity::create([
                'name' => 'Marietta Telyie',
                'category_id' => '1',
                'code' => '0.007.',
            ]);

            Entity::create([
                'name' => 'Luis Osvaldo Moya Abarca',
                'category_id' => '1',
                'code' => '0.008.',
            ]);
        }

        /**Colectivos */
        {
            Entity::create([
                'name' => 'null',
                'category_id' => '2',
                'code' => '1.001.',
            ]);
        }

        /**Comunidades */ {
            Entity::create([
                'name' => 'Comunidad Edificio Bicentenario Lord Cochrane',
                'category_id' => '3',
                'code' => '2.001.',
            ]);

            Entity::create([
                'name' => 'Comunidad Edificio Blanco Norte',
                'category_id' => '3',
                'code' => '2.002.',
            ]);
        }

        /**Empresas */
        {
            Entity::create([
                'name' => 'manTcic Life',
                'category_id' => '4',
                'code' => '3.001.',
            ]);

            Entity::create([
                'name' => 'pepeSky Ingeniría Cúantica',
                'category_id' => '4',
                'code' => '3.002.',
            ]);
        }

         /**Fiscal */
         {
            Entity::create([
                'name' => 'Servicios Impuestos Internos',
                'category_id' => '6',
                'code' => '5.001.',
            ]);

         }

    }
}
