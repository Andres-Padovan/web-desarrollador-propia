<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'slug' => Str::slug($title),
            'title' => ['es' => $title, 'en' => fake()->sentence(4)],
            'excerpt' => ['es' => fake()->sentence(), 'en' => fake()->sentence()],
            'body' => ['es' => "## Introducción\n\n".fake()->paragraph(), 'en' => "## Intro\n\n".fake()->paragraph()],
            'category' => 'web',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ];
    }

    public function draft(): static
    {
        return $this->state(['is_published' => false]);
    }
}
