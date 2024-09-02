<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
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
            'descricao' => substr($descricao, 0, 255),
            'ativo' => true,
        ];
    }
}
