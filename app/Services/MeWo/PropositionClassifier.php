<?php

namespace App\Services\MeWo;

use App\Models\OperationalVerb;
use Illuminate\Support\Str;

class PropositionClassifier
{
    /** Suggestions only: no proposition, activity or context is automatically created. */
    public function suggest(string $sentence): array
    {
        preg_match_all('/[\p{L}]+/u', $sentence, $matches, PREG_OFFSET_CAPTURE);
        $words = $matches[0];
        $tokens = array_map(fn ($word) => Str::lower(Str::ascii($word[0])), $words);
        $result = [];
        foreach (OperationalVerb::with('processes.context')->get() as $verb) {
            $forms = array_map(fn ($form) => Str::lower(Str::ascii($form)), array_merge([$verb->infinitive], $verb->forms ?? []));
            $matched = array_values(array_intersect($tokens, $forms));
            if (! $matched) {
                continue;
            }
            $position = array_search($matched[0], $tokens, true);
            $tail = substr($sentence, $words[$position][1] + strlen($words[$position][0]));
            $complement = trim(preg_split('/[.!?;]/u', $tail, 2)[0]);
            $negated = in_array('no', array_slice($tokens, max(0, $position - 2), min(2, $position)), true);
            foreach ($verb->processes as $process) {
                $result[] = [
                    'verb' => $verb->infinitive, 'matched' => $matched,
                    'complement_fragment' => $complement, 'negated' => $negated,
                    'process_id' => $process->id, 'process' => $process->name,
                    'context_id' => $process->context_id, 'context' => $process->context->name,
                ];
            }
        }
        return $result;
    }
}
