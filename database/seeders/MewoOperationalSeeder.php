<?php

namespace Database\Seeders;

use App\Models\Context;
use App\Models\MewoProcess;
use App\Models\OperationalVerb;
use App\Services\MeWo\ContextComposition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MewoOperationalSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->call(ContextSeeder::class);
            $simple = Context::where('type', 'simple')->pluck('id', 'code');
            foreach (Context::where('type', 'ampliado')->get() as $expanded) {
                $codes = explode('+', $expanded->code);
                if (count($codes) > 1 && collect($codes)->every(fn ($code) => isset($simple[$code]))) {
                    app(ContextComposition::class)->create(array_map(fn ($code) => $simple[$code], $codes));
                }
            }
            $catalog = json_decode(file_get_contents(__DIR__.'/data/mewo_processes.json'), true, 512, JSON_THROW_ON_ERROR);
            foreach ($catalog as $contextCode => $definitions) {
                $context = Context::where('code', $contextCode)->firstOrFail();
                foreach ($definitions as [$code, $name, $verbs]) {
                    $process = MewoProcess::firstOrCreate(['context_id' => $context->id, 'code' => $code], ['name' => $name]);
                    foreach ($verbs as $infinitive) {
                        $verb = OperationalVerb::firstOrCreate(['infinitive' => $infinitive], ['forms' => $this->forms($infinitive)]);
                        $process->verbs()->syncWithoutDetaching([$verb->id]);
                    }
                }
            }
        });
    }

    private function forms(string $verb): array
    {
        // Only curated forms; do not guess lemmas by stripping arbitrary suffixes.
        return match ($verb) {
            'reparar' => ['reparo', 'reparas', 'repara', 'reparamos', 'reparan', 'reparé', 'reparó', 'repararon', 'reparando'],
            'planificar' => ['planifico', 'planifica', 'planificamos', 'planifican', 'planifiqué', 'planificó', 'planificando'],
            'organizar' => ['organizo', 'organiza', 'organizamos', 'organizan', 'organicé', 'organizó'],
            'analizar' => ['analizo', 'analiza', 'analizamos', 'analizan', 'analicé', 'analizó'],
            'escribir' => ['escribo', 'escribe', 'escribimos', 'escriben', 'escribí', 'escribió', 'escribiendo'],
            'mantener' => ['mantengo', 'mantiene', 'mantenemos', 'mantienen', 'mantuve', 'mantuvo'],
            default => [],
        };
    }
}
