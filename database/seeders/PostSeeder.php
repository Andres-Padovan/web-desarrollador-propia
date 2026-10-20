<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PostSeeder extends Seeder
{
    /**
     * First articles of the blog. Each one lives in content/blog as {slug}.es.md and {slug}.en.md,
     * with the title and excerpt in a small frontmatter block.
     *
     * @var array<string, array{category: string, published_at: string}>
     */
    private const ARTICLES = [
        'como-elegir-desarrollador-web' => ['category' => 'business', 'published_at' => '2026-09-22 10:00'],
        'por-que-tu-landing-no-convierte' => ['category' => 'web', 'published_at' => '2026-09-29 10:00'],
        'ia-para-negocios-por-donde-empezar' => ['category' => 'ai', 'published_at' => '2026-10-06 10:00'],
        'chatbots-ia-automatizacion' => ['category' => 'ai', 'published_at' => '2026-10-13 10:00'],
    ];

    public function run(): void
    {
        foreach (self::ARTICLES as $slug => $meta) {
            $versions = collect(['es', 'en'])->mapWithKeys(fn (string $locale) => [$locale => $this->read($slug, $locale)]);

            Post::updateOrCreate(['slug' => $slug], [
                'title' => $versions->map(fn (array $version) => $version['title'])->all(),
                'excerpt' => $versions->map(fn (array $version) => $version['excerpt'])->all(),
                'body' => $versions->map(fn (array $version) => $version['body'])->all(),
                'category' => $meta['category'],
                'is_published' => true,
                'published_at' => Carbon::parse($meta['published_at'], config('profile.timezone')),
            ]);
        }
    }

    /** @return array{title: string, excerpt: string, body: string} */
    private function read(string $slug, string $locale): array
    {
        $contents = file_get_contents(__DIR__."/content/blog/{$slug}.{$locale}.md");

        preg_match('/\A---\s*\n(.*?)\n---\s*\n(.*)\z/s', $contents, $parts);
        preg_match_all('/^(\w+):\s*"(.*)"\s*$/m', $parts[1], $fields, PREG_SET_ORDER);

        $frontmatter = collect($fields)->mapWithKeys(fn (array $field) => [$field[1] => stripslashes($field[2])]);

        return ['title' => $frontmatter['title'], 'excerpt' => $frontmatter['excerpt'], 'body' => trim($parts[2])];
    }
}
