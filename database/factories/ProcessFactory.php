<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProcessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(),
            'url' => 'https://youtu.be/i07qz_6Mk7g',
            'iframe' => '<iframe width="853" height="480" src="https://www.youtube.com/embed/i07qz_6Mk7g" title="¿Te Atreves A Soñar? | Desafía tu Zona de Confort!" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            'platform_id' => 1
        ];
    }
}
