<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName().' '.fake()->randomLetter().'.',
            'project_type' => ['es' => 'Sitio web', 'en' => 'Website'],
            'quote' => ['es' => fake()->sentence(), 'en' => fake()->sentence()],
            'rating' => 5,
            'is_visible' => true,
        ];
    }
}
