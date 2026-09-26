<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        // La planilla distingue unidades con el mismo símbolo mediante el sistema.
        $catalogue = [
            ['Metro', 'Longitud', 'Internacional (SI)', 'm', 'Base'],
            ['Kilogramo', 'Masa', 'Internacional (SI)', 'kg', 'Base'],
            ['Segundo', 'Tiempo', 'Internacional (SI)', 's', 'Base'],
            ['Ampere', 'Corriente eléctrica', 'Internacional (SI)', 'A', 'Base'],
            ['Newton', 'Fuerza', 'Internacional (SI)', 'N', 'Derivada'],
            ['Joule', 'Energía', 'Internacional (SI)', 'J', 'Derivada'],
            ['Watt', 'Potencia', 'Internacional (SI)', 'W', 'Derivada'],
            ['Pascal', 'Presión', 'Internacional (SI)', 'Pa', 'Derivada'],
            ['Hertz', 'Frecuencia', 'Internacional (SI)', 'Hz', 'Derivada'],
            ['Coulomb', 'Carga eléctrica', 'Internacional (SI)', 'C', 'Derivada'],
            ['Inch', 'Longitud', 'Inglés', 'in', 'Base'],
            ['Foot', 'Longitud', 'Inglés', 'ft', 'Base'],
            ['Yard', 'Longitud', 'Inglés', 'yd', 'Base'],
            ['Mile', 'Longitud', 'Inglés', 'mi', 'Base'],
            ['Pound', 'Masa', 'Inglés', 'lb', 'Base'],
            ['Pound-force', 'Fuerza', 'Inglés', 'lbf', 'Derivada'],
            ['BTU', 'Energía', 'Inglés', 'BTU', 'Derivada'],
            ['Horsepower', 'Potencia', 'Inglés', 'hp', 'Derivada'],
            ['Fahrenheit', 'Temperatura', 'Inglés', '°F', 'Base'],
            ['PSI', 'Presión', 'Inglés', 'psi', 'Derivada'],
            ['Centímetro', 'Longitud', 'CGS', 'cm', 'Base'],
            ['Gramo', 'Masa', 'CGS', 'g', 'Base'],
            ['Segundo', 'Tiempo', 'CGS', 's', 'Base'],
            ['Dina', 'Fuerza', 'CGS', 'dyn', 'Derivada'],
            ['Erg', 'Energía', 'CGS', 'erg', 'Derivada'],
            ['Minuto', 'Tiempo', 'Temporal', 'min', 'Escalar'],
            ['Hora', 'Tiempo', 'Temporal', 'h', 'Escalar'],
            ['Día', 'Tiempo', 'Temporal', 'd', 'Escalar'],
            ['Semana', 'Tiempo', 'Temporal', 'sem', 'Escalar'],
            ['Mes', 'Tiempo', 'Temporal', 'mes', 'Escalar'],
            ['Año', 'Tiempo', 'Temporal', 'a', 'Escalar'],
            ['Peso Chileno', 'Valor', 'Monetario', 'CLP', 'Monetaria'],
            ['Dólar', 'Valor', 'Monetario', 'USD', 'Monetaria'],
            ['Euro', 'Valor', 'Monetario', 'EUR', 'Monetaria'],
            ['Bit', 'Información', 'Informático', 'bit', 'Base'],
            ['Byte', 'Información', 'Informático', 'B', 'Base'],
            ['Kilobyte', 'Información', 'Informático', 'KB', 'Escalar'],
            ['Megabyte', 'Información', 'Informático', 'MB', 'Escalar'],
            ['Gigabyte', 'Información', 'Informático', 'GB', 'Escalar'],
            ['Negocio', 'Trabajo', 'SyQuAc', 'UnNE', 'Monetaria'],
            ['Servicio', 'Trabajo', 'SyQuAc', 'UnSe', 'Monetaria'],
            ['Soporte', 'Trabajo', 'SyQuAc', 'UnSo', 'Monetaria'],
            ['Operativa', 'Trabajo', 'SyQuAc', 'UnOp', 'Monetaria'],
            ['Elemental', 'Trabajo', 'SyQuAc', 'UnEl', 'Monetaria'],
        ];

        // Conservar el ID de Negocio cuando proviene del cargador anterior.
        if (! Unit::where('symbol', 'UnNE')->exists()) {
            Unit::where('symbol', 'UnNe')->update(['symbol' => 'UnNE']);
        }

        foreach ($catalogue as [$name, $magnitude, $system, $symbol, $type]) {
            $existing = $system === 'SyQuAc'
                ? Unit::where('symbol', $symbol)->first()
                : Unit::where('name', $name)->where('system', $system)->first();

            if ($existing) {
                $existing->update(compact('name', 'magnitude', 'system', 'symbol', 'type'));
            } else {
                Unit::create(compact('name', 'magnitude', 'system', 'symbol', 'type'));
            }
        }
    }
}
