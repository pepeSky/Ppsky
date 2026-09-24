<?php

namespace App\Services\SyQuAc;

use App\Models\Uf;
use App\Services\BancoCentral\BdeService;
use Carbon\Carbon;

class UfService
{
    private const SERIE = 'F073.UFF.PRE.Z.D';

    public function __construct(
        protected BdeService $bde
    ) {
    }

    public function serie(): string
    {
        return self::SERIE;
    }

    /**
     * Obtiene observaciones UF directamente
     * desde el Banco Central.
     */
    public function consultar(
        Carbon|string $fechaInicio,
        Carbon|string $fechaFin
    ): array {
        $fechaInicio = Carbon::parse($fechaInicio)->toDateString();
        $fechaFin = Carbon::parse($fechaFin)->toDateString();

        return $this->bde->obtenerSerie(
            self::SERIE,
            $fechaInicio,
            $fechaFin
        );
    }

    /**
     * Obtiene una UF almacenada localmente.
     */
    public function obtener(Carbon|string $fecha): ?Uf
    {
        $fecha = Carbon::parse($fecha)->toDateString();

        return Uf::whereDate('fecha', $fecha)->first();
    }

    /**
     * Obtiene la última UF almacenada.
     */
    public function ultima(): ?Uf
    {
        return Uf::orderByDesc('fecha')->first();
    }
    
    public function sincronizar(
        Carbon|string $fechaInicio,
        Carbon|string $fechaFin
    ): int {
        $fechaInicio = Carbon::parse($fechaInicio)->toDateString();
        $fechaFin = Carbon::parse($fechaFin)->toDateString();

        $observaciones = $this->consultar(
            $fechaInicio,
            $fechaFin
        );

        $registros = 0;

        foreach ($observaciones as $observacion) {

            if (($observacion['statusCode'] ?? null) !== 'OK') {
                continue;
            }

            $fecha = Carbon::createFromFormat(
                'd-m-Y',
                $observacion['indexDateString']
            )->toDateString();

            Uf::updateOrCreate(
                [
                    'fecha' => $fecha,
                ],
                [
                    'valor' => $observacion['value'],
                    'fuente' => 'Banco Central de Chile',
                    'codigo' => 'UF',
                    'consultado_at' => now(),
                ]
            );

            $registros++;
        }

        return $registros;
    }
}
