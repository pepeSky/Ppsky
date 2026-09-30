<?php

namespace Database\Seeders;

use App\Models\Context;
use App\Models\MewoProcess;
use Illuminate\Database\Seeder;

class MewoProcessSeeder extends Seeder
{
    public function run(): void
    {
        $administration = Context::where('code', 'ADM')->firstOrFail();

        $processes = [
            [
                'code' => 'PLN',
                'name' => 'Planificación',
                'description' => 'Proceso administrativo de planificación.',
            ],
            [
                'code' => 'ORG',
                'name' => 'Organización',
                'description' => 'Proceso administrativo de organización.',
            ],
            [
                'code' => 'DIR',
                'name' => 'Dirección',
                'description' => 'Proceso administrativo de dirección y ejecución.',
            ],
            [
                'code' => 'CTL',
                'name' => 'Control',
                'description' => 'Proceso administrativo de control.',
            ],
        ];

        foreach ($processes as $process) {
            MewoProcess::updateOrCreate(
                [
                    'context_id' => $administration->id,
                    'code' => $process['code'],
                ],
                [
                    'name' => $process['name'],
                    'description' => $process['description'],
                ]
            );
        }
    }
}
