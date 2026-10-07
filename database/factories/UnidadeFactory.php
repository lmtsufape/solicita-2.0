<?php

namespace Database\Factories;

use App\Models\Instituicao;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnidadeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->word,
            'instituicao_id' => Instituicao::factory(),
        ];
    }
}
