<?php

namespace Database\Factories;

use App\Enums\CheckResult;
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
            'result' => fake()->randomElement(CheckResult::cases()),
            'status' => fake()->randomElement([200, 201, 202, 204, 206, 300, 301, 302, 307, 308, 400, 401, 403, 404, 405, 408, 422, 429, 500, 502, 503]),
            'duration' => fake()->numberBetween(50, 2000),
        ];
    }
}
