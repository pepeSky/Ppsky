<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'name' => 'Español',
                'code' => 'es',
                'description' => 'Lenguaje utilizado para codificar expresiones en español.',
            ],
            [
                'name' => 'Inglés',
                'code' => 'en',
                'description' => 'Lenguaje utilizado para codificar expresiones en inglés.',
            ],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['name' => $language['name']],
                $language
            );
        }
    }
}
