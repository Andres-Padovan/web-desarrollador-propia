<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(2, true);

        return [
            'slug' => \Illuminate\Support\Str::slug($title),
            'title' => ['es' => ucfirst($title), 'en' => ucfirst($title)],
            'summary' => ['es' => fake()->sentence(), 'en' => fake()->sentence()],
            'category' => ['es' => 'Sitio web', 'en' => 'Website'],
            'stack' => ['Laravel', 'Tailwind'],
            'status' => 'live',
            'is_visible' => true,
        ];
    }
}
