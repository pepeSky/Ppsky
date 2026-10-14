<?php

namespace App\Services\MeWo;

use App\Models\Objetive;
use Illuminate\Support\Facades\Schema;

class QuantumObjectives
{
    public function available(int $projectId)
    {
        if (! Schema::hasColumn('objetives', 'parent_id')) {
            return collect();
        }
        $objectives = Objetive::where('project_id', $projectId)->get()->keyBy('id');
        return $objectives->filter(function ($objective) use ($objectives) {
            $depth = 0;
            $seen = [$objective->id];
            $node = $objective;
            while ($node->parent_id) {
                $node = $objectives->get($node->parent_id);
                if (! $node || in_array($node->id, $seen, true)) {
                    return false;
                }
                $seen[] = $node->id;
                $depth++;
            }
            return $depth === 3;
        })->values();
    }
}
