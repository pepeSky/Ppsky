<?php

namespace App\Services\MeWo;

use App\Models\Application;
use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DictionaryTransfer
{
    public function export(Application $application): array
    {
        return [
            'format' => 'pepesky.dictionary', 'version' => 1,
            'application' => $application->name,
            'terms' => $application->models()->with('definitions.language')->orderBy('name')->get()->map(fn ($model) => [
                'name' => $model->name, 'description' => $model->description,
                'definitions' => $model->definitions->map(fn ($definition) => [
                    'definition' => $definition->definition,
                    'language' => $definition->language->only(['name', 'code', 'description']),
                ])->all(),
            ])->all(),
        ];
    }

    public function import(User $user, Application $application, array $payload): array
    {
        $data = Validator::make($payload, [
            'format' => ['required', 'in:pepesky.dictionary'], 'version' => ['required', 'integer', 'in:1'],
            'terms' => ['present', 'array', 'max:1000'],
            'terms.*.name' => ['required', 'string', 'max:255', 'regex:/\S/u', 'distinct'],
            'terms.*.description' => ['nullable', 'string', 'max:10000'],
            'terms.*.definitions' => ['present', 'array', 'max:100'],
            'terms.*.definitions.*.definition' => ['required', 'string', 'max:10000'],
            'terms.*.definitions.*.language.name' => ['required', 'string', 'max:255'],
            'terms.*.definitions.*.language.code' => ['nullable', 'string', 'max:255'],
            'terms.*.definitions.*.language.description' => ['nullable', 'string', 'max:10000'],
        ])->validate();

        return DB::transaction(function () use ($user, $application, $data) {
            // Serialize imports for this user's existing dictionary name constraint.
            User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $result = ['added' => 0, 'existing' => 0, 'conflicts' => []];
            foreach ($data['terms'] as $term) {
                $model = $user->models()->where('name', $term['name'])->first();
                $conflict = $model && (string) $model->description !== (string) ($term['description'] ?? '');
                $termLanguages = [];
                foreach ($term['definitions'] as $definition) {
                    $languageKey = mb_strtolower($definition['language']['name']);
                    $signature = [(string) ($definition['language']['code'] ?? '')];
                    if (isset($termLanguages[$languageKey]) && $termLanguages[$languageKey] !== $signature) { $conflict = true; }
                    $termLanguages[$languageKey] = $signature;
                    $language = Language::where('name', $definition['language']['name'])->first();
                    if ($language && ((string) $language->code !== (string) ($definition['language']['code'] ?? ''))) {
                        $conflict = true;
                    }
                }
                if ($conflict) { $result['conflicts'][] = $term['name']; continue; }
                $model ??= $user->models()->create(['name' => $term['name'], 'description' => $term['description'] ?? null]);
                foreach ($term['definitions'] as $definition) {
                    $language = Language::firstOrCreate(['name' => $definition['language']['name']], [
                        'code' => $definition['language']['code'] ?? null,
                        'description' => $definition['language']['description'] ?? null,
                    ]);
                    $model->definitions()->firstOrCreate(['language_id' => $language->id, 'definition' => $definition['definition']]);
                }
                $changes = $application->associateModels([$model->id]);
                $result[count($changes['attached']) ? 'added' : 'existing']++;
            }
            return $result;
        });
    }
}
