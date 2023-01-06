<?php

namespace Database\Factories;

use App\Enums\CheckStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Check>
 */
class CheckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'status' => $status = fake()->randomElement(CheckStatus::cases()),
            'duration' => fake()->numberBetween(50, 2000),
            'finished_at' => $status === CheckStatus::Successful ? now() : null,
            'response' => [
                'http_status' => fake()->randomElement([200, 201, 202, 204, 206, 300, 301, 302, 307, 308, 400, 401, 403, 404, 405, 408, 422, 429, 500, 502, 503]),
            ],
        ];
    }
}
