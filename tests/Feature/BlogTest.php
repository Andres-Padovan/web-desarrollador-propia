<?php

namespace Tests\Feature;

use App\Models\Post;
use Database\Seeders\PostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_lists_only_published_articles_newest_first(): void
    {
        Post::factory()->create(['title' => ['es' => 'Artículo viejo', 'en' => 'Old article'], 'published_at' => now()->subWeek()]);
        Post::factory()->create(['title' => ['es' => 'Artículo nuevo', 'en' => 'New article'], 'published_at' => now()->subDay()]);
        Post::factory()->draft()->create(['title' => ['es' => 'Borrador', 'en' => 'Draft']]);
        Post::factory()->create(['title' => ['es' => 'Programado', 'en' => 'Scheduled'], 'published_at' => now()->addDay()]);

        $this->get(route('blog'))
            ->assertOk()
            ->assertSeeInOrder(['Artículo nuevo', 'Artículo viejo'])
            ->assertDontSee('Borrador')
            ->assertDontSee('Programado');

        $this->get(route('blog.en'))->assertOk()->assertSee('New article');
    }

    public function test_article_renders_markdown_in_each_language_and_hides_drafts(): void
    {
        $post = Post::factory()->create([
            'slug' => 'ia-para-negocios',
            'title' => ['es' => 'IA para tu negocio', 'en' => 'AI for your business'],
            'body' => ['es' => "## Por dónde empezar\n\nUn **primer paso** simple.\n\n<script>alert(1)</script>", 'en' => "## Where to start\n\nA simple **first step**."],
        ]);

        $this->get('/blog/ia-para-negocios')
            ->assertOk()
            ->assertSee('<h2>Por dónde empezar</h2>', false)
            ->assertSee('<strong>primer paso</strong>', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('"@type":"BlogPosting"', false)
            ->assertSee('<link rel="alternate" hreflang="en" href="'.url('/en/blog/ia-para-negocios').'">', false);

        $this->get('/en/blog/ia-para-negocios')->assertOk()->assertSee('<h2>Where to start</h2>', false);

        $post->update(['is_published' => false]);
        $this->get('/blog/ia-para-negocios')->assertNotFound();
    }

    public function test_seeded_articles_load_both_languages_from_markdown_files(): void
    {
        $this->seed(PostSeeder::class);

        $post = Post::where('slug', 'chatbots-ia-automatizacion')->firstOrFail();

        $this->assertNotEmpty($post->localized('title', 'es'));
        $this->assertNotEmpty($post->localized('title', 'en'));
        $this->assertNotSame($post->localized('body', 'es'), $post->localized('body', 'en'));
        $this->assertSame(4, Post::published()->count());
    }
}
