<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => ['es' => fake()->words(3, true), 'en' => fake()->words(3, true)],
            'description' => ['es' => fake()->sentence(), 'en' => fake()->sentence()],
            'stack' => ['Laravel'],
            'span' => 1,
            'is_visible' => true,
        ];
    }
}
