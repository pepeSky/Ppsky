<?php

namespace App\Services\MeWo;

use App\Models\Context;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ContextComposition
{
    public function create(array $ids): Context
    {
        $ids = array_map('intval', array_values($ids));
        if (count($ids) < 2 || count($ids) > 12) {
            throw ValidationException::withMessages(['component_ids' => 'Seleccione entre dos y doce componentes.']);
        }
        $counts = array_count_values($ids);
        $repeated = array_filter($counts, fn ($count) => $count > 1);
        // Only the initial observer may expand into its own image once.
        if ($repeated && (count($repeated) !== 1 || max($repeated) !== 2 || $ids[0] !== $ids[1])) {
            throw ValidationException::withMessages(['component_ids' => 'La única repetición permitida es la pareja inicial X + X.']);
        }
        $contexts = Context::whereIn('id', $ids)->where('type', 'simple')->get()->keyBy('id');
        if ($contexts->count() !== count($counts)) {
            throw ValidationException::withMessages(['component_ids' => 'Todos los componentes deben ser contextos simples.']);
        }
        return DB::transaction(function () use ($ids, $contexts) {
            $code = implode('+', array_map(fn ($id) => $contexts[$id]->code, $ids));
            $context = Context::firstOrCreate(['code' => $code], [
                'name' => implode(' + ', array_map(fn ($id) => $contexts[$id]->name, $ids)), 'type' => 'ampliado',
            ]);
            if (! $context->components()->exists()) {
                foreach ($ids as $position => $id) {
                    DB::table('context_components')->insert(['context_id' => $context->id, 'component_id' => $id, 'position' => $position]);
                }
            }
            return $context;
        });
    }
}
