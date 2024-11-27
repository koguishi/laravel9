<?php

namespace Database\Factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
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
            'titulo' => $this->faker->name(),
            'descricao' => $this->faker->sentence(),
            'dt_filmagem' => $this->valid_dtFilmagem(),
        ];
    }

    public function valid_dtFilmagem(): DateTime
    {
        $strApenasData = $this->faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d');
        return new DateTime($strApenasData);
    }
}
