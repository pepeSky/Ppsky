<?php

namespace Database\Seeders;

use App\Models\Modules\Study\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lesson::create([
            'lesson' => '1.1. El contexto: los sistemas económicos actuales y emergentes.',
            'section_id' => '1'
        ]);

        Lesson::create([
            'lesson' => '1.2. La productividad agregada y las Organizaciones.',
            'section_id' => '1'
        ]);

        Lesson::create([
            'lesson' => '1.3. Los negocios y las formas de organizarse.',
            'section_id' => '1'
        ]);

        Lesson::create([
            'lesson' => '2.1. Análisis Organizacional.',
            'section_id' => '2'
        ]);

        Lesson::create([
            'lesson' => '2.2. Nuevas habilidades del administrador.',
            'section_id' => '2'
        ]);

        Lesson::create([
            'lesson' => '2.3. Liderazgo creativo.',
            'section_id' => '2'
        ]);

        Lesson::create([
            'lesson' => '3.1. Comprensión del proceso administrativo y sus desafíos en el mundo actual.',
            'section_id' => '3'
        ]);

        Lesson::create([
            'name' => ' 3.2. La Toma de decisiones en las áreas funcionales.',
            'section_id' => '3'
        ]);

        Lesson::create([
            'name' => '3.3. Toma de decisiones administrativas y su relación con el proceso y áreas funcionales.',
            'section_id' => '3'
        ]);

        Lesson::create([
            'name' => '4.1. Comportamiento ético en las Organizaciones.',
            'section_id' => '4'
        ]);

        Lesson::create([
            'name' => '4.2. La triple cuenta de resultados: económico, social y medioambiental.',
            'section_id' => '4'
        ]);

    }
}
