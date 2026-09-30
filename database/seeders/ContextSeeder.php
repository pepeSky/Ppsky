<?php

namespace Database\Seeders;

use App\Models\Context;
use Illuminate\Database\Seeder;

class ContextSeeder extends Seeder
{
    public function run(): void
    {
        $contexts = [
            ['code' => 'REF', 'name' => 'Reflexividad', 'type' => 'simple'],
            ['code' => 'ADM', 'name' => 'Administración', 'type' => 'simple'],
            ['code' => 'SAL', 'name' => 'Salud', 'type' => 'simple'],
            ['code' => 'COM', 'name' => 'Comercial', 'type' => 'simple'],
            ['code' => 'FIN', 'name' => 'Finanzas', 'type' => 'simple'],
            ['code' => 'APR', 'name' => 'Aprovisionamiento', 'type' => 'simple'],
            ['code' => 'MAN', 'name' => 'Mantenimiento', 'type' => 'simple'],
            ['code' => 'CAP', 'name' => 'Capacitación', 'type' => 'simple'],
            ['code' => 'DES', 'name' => 'Desarrollo', 'type' => 'simple'],
            ['code' => 'JUR', 'name' => 'Jurídico', 'type' => 'simple'],
            ['code' => 'CMN', 'name' => 'Comunicación', 'type' => 'simple'],

            ['code' => 'ADM+REF', 'name' => 'Administración + Reflexividad', 'type' => 'ampliado'],
            ['code' => 'ADM+SAL', 'name' => 'Administración + Salud', 'type' => 'ampliado'],
            ['code' => 'ADM+COM', 'name' => 'Administración + Comercial', 'type' => 'ampliado'],
            ['code' => 'ADM+FIN', 'name' => 'Administración + Finanzas', 'type' => 'ampliado'],
            ['code' => 'ADM+APR', 'name' => 'Administración + Aprovisionamiento', 'type' => 'ampliado'],
            ['code' => 'ADM+MAN', 'name' => 'Administración + Mantenimiento', 'type' => 'ampliado'],
            ['code' => 'ADM+CAP', 'name' => 'Administración + Capacitación', 'type' => 'ampliado'],
            ['code' => 'ADM+DES', 'name' => 'Administración + Desarrollo', 'type' => 'ampliado'],
            ['code' => 'ADM+JUR', 'name' => 'Administración + Jurídico', 'type' => 'ampliado'],
            ['code' => 'ADM+CMN', 'name' => 'Administración + Comunicación', 'type' => 'ampliado'],
        ];

        foreach ($contexts as $context) {
            Context::updateOrCreate(
                ['code' => $context['code']],
                [
                    'name' => $context['name'],
                    'type' => $context['type'],
                ]
            );
        }
    }
}
