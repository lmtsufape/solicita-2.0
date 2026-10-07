<?php

namespace Database\Factories;

use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Perfil;
use App\Models\Unidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerfilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'default' => $this->faker->word,
            'situacao' => $this->faker->randomElement(Perfil::SITUACAO_ENUM),
            'valor' => $this->faker->boolean(),
            'aluno_id' => Aluno::factory(),
            'unidade_id' => Unidade::factory(),
            'curso_id' => Curso::factory(),
        ];
    }
}
