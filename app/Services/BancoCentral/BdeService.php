<?php

namespace App\Services\BancoCentral;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class BdeService
{
    protected string $url;
    protected ?string $token;

    public function __construct()
    {
        $this->url = config('services.bcentral.url');
        $this->token = config('services.bcentral.token');
    }

    /**
     * Obtiene las observaciones de una serie BDE.
     */
    public function obtenerSerie(
        string $serie,
        string $fechaInicio,
        string $fechaFin
    ): array {
        if (empty($this->token)) {
            throw new RuntimeException(
                'No se ha configurado BCENTRAL_TOKEN.'
            );
        }

        $response = Http::acceptJson()
            ->timeout(20)
            ->get($this->url, [
                'token'     => $this->token,
                'function'  => 'GetSeries',
                'timeseries'=> $serie,
                'firstdate' => $fechaInicio,
                'lastdate'  => $fechaFin,
            ]);

        $response->throw();

        $datos = $response->json();

        $codigo = $datos['Codigo'] ?? null;

        if ($codigo !== 0) {
            throw new RuntimeException(
                'Banco Central BDE [' . ($codigo ?? '?') . ']: ' .
                ($datos['Descripcion'] ?? 'Error desconocido.')
            );
        }

        if (!isset($datos['Series']['Obs'])) {
            throw new RuntimeException(
                'La respuesta del Banco Central no contiene Series.Obs.'
            );
        }

        return $datos['Series']['Obs'];
    }
}
