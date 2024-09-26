<?php

namespace Database\Factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\app\Models\Atleta>
 */
class AtletaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $descricao = $this->faker->sentence(10);
        return [
            'id' => (string) Str::uuid(),
            'nome' => $this->faker->name(),
            'dtNascimento' => $this->dataAtualMenos10Anos(),
        ];
    }

    private function dataAtualMenos10Anos() : DateTime
    {
        // Cria um objeto DateTime com a data atual
        $data = new DateTime(today());
        // Subtrai 100 anos
        return $data->modify('-10 years');
    }    
}
