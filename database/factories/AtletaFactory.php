<?php

namespace Database\Factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Atleta>
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
        return [
            'id' => (string) Str::uuid(),
            'nome' => $this->faker->name(),
            'dtNascimento' => $this->faker->dateTimeBetween('-100 years', 'now'),
        ];
    }

    public function valid_dtNascimento(): DateTime
    {
        return $this->faker->dateTimeBetween('-100 years', 'now');
    }
}
