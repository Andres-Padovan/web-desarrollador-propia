<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_spanish_home_renders_profile_and_published_content(): void
    {
        Service::factory()->create(['title' => ['es' => 'Servicio visible', 'en' => 'Visible service']]);
        Project::factory()->create(['title' => ['es' => 'Proyecto público', 'en' => 'Public project']]);
        Project::factory()->create(['title' => ['es' => 'Proyecto oculto', 'en' => 'Hidden project'], 'is_visible' => false]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<html lang="es"', false)
            ->assertSee('Andrés Padovan')
            ->assertSee('Servicio visible')
            ->assertSee('Proyecto público')
            ->assertDontSee('Proyecto oculto')
            ->assertSee('wa.me/5492634625076', false)
            ->assertSee('application/ld+json', false);
    }

    public function test_links_use_https_host_when_served_through_a_tunnel(): void
    {
        $this->withHeaders(['X-Forwarded-Proto' => 'https', 'X-Forwarded-Host' => 'demo.trycloudflare.com'])
            ->get(route('home'))
            ->assertOk()
            ->assertSee('https://demo.trycloudflare.com/', false)
            ->assertDontSee('http://localhost/', false);
    }

    public function test_english_home_uses_english_copy_and_falls_back_to_spanish(): void
    {
        Project::factory()->create(['title' => ['es' => 'Solo en español', 'en' => '']]);

        $this->get(route('home.en'))
            ->assertOk()
            ->assertSee('<html lang="en"', false)
            ->assertSee('works for you.')
            ->assertSee('Solo en español');
    }

    public function test_project_tour_video_is_served_from_bundled_and_uploaded_paths(): void
    {
        Project::factory()->create(['image' => 'images/proyectos/demo.webp', 'video' => 'videos/proyectos/demo.mp4']);
        Project::factory()->create(['video' => 'projects/videos/subido.mp4']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(asset('videos/proyectos/demo.mp4'), false)
            ->assertSee('poster="'.asset('images/proyectos/demo.webp').'"', false)
            ->assertSee('/storage/projects/videos/subido.mp4', false)
            ->assertSee('Ver recorrido');
    }

    public function test_testimonials_section_only_appears_when_there_are_reviews(): void
    {
        $this->get(route('home'))->assertDontSee('id="resenas"', false);

        Testimonial::factory()->create(['name' => 'María G.']);

        $this->get(route('home'))->assertSee('id="resenas"', false)->assertSee('María G.');
    }

    public function test_sitemap_lists_both_languages(): void
    {
        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('home.en'), false)
            ->assertSee('hreflang="en"', false);
    }
}
