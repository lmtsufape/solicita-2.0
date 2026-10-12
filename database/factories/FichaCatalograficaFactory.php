<?php

namespace Database\Factories;

use App\Models\Aluno;
use App\Models\FichaCatalografica;
use Illuminate\Database\Eloquent\Factories\Factory;

class FichaCatalograficaFactory extends Factory
{

    protected $model = FichaCatalografica::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'aluno_id' => Aluno::factory(),

            'autor_nome' => $this->faker->firstName(),
            'autor_sobrenome' => $this->faker->lastName(),

            'cutter' => strtoupper(
                $this->faker->randomLetter() .
                $this->faker->numberBetween(100, 999)
            ),

            'area_conhecimento' => $this->faker->randomElement([
                'Ciência da Computação',
                'Educação',
                'Matemática',
                'Psicologia',
            ]),

            'titulo' => $this->faker->sentence(5),

            'subtitulo' => $this->faker->optional()->sentence(4),

            'local' => 'Garanhuns',

            'ano' => $this->faker->numberBetween(2020, 2026),

            'folhas' => $this->faker->numberBetween(30, 200),

            'ilustracao' => $this->faker->randomElement([
                'Sim',
                'Não',
            ]),

            'anexo' => $this->faker->uuid . '.pdf',

            'inclui_anexo' => false,

            'inclui_apendice' => false,

            'tipo_ficha_catalografica_id' => $this->faker->numberBetween(1, 4),

            'nome_orientador' => $this->faker->firstName(),

            'sobrenome_orientador' => $this->faker->lastName(),
        ];
    }
}
