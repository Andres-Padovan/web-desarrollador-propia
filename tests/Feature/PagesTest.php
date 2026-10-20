<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_section_has_its_own_page_in_both_languages(): void
    {
        Service::factory()->create(['title' => ['es' => 'Sistemas de gestión', 'en' => 'Management systems']]);

        $this->get('/servicios')->assertOk()->assertSee('<html lang="es"', false)->assertSee('Sistemas de gestión');
        $this->get('/en/services')->assertOk()->assertSee('<html lang="en"', false)->assertSee('Management systems');
        $this->get('/proceso')->assertOk()->assertSee('id="proceso"', false);
        $this->get('/en/process')->assertOk()->assertSee('id="proceso"', false);
        $this->get('/sobre-mi')->assertOk()->assertSee('id="sobre-mi"', false);
        $this->get('/en/about')->assertOk()->assertSee('id="sobre-mi"', false);
    }

    public function test_home_shows_three_projects_and_links_to_the_full_list(): void
    {
        Project::factory()->count(5)->sequence(fn ($sequence) => ['title' => ['es' => 'Proyecto '.$sequence->index, 'en' => 'Project '.$sequence->index], 'sort' => $sequence->index])->create();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Proyecto 2')
            ->assertDontSee('Proyecto 3')
            ->assertSee('Ver los 5 proyectos')
            ->assertSee(route('projects'), false);

        $this->get(route('projects'))
            ->assertOk()
            ->assertSee('Proyecto 0')
            ->assertSee('Proyecto 4');
    }

    public function test_project_page_shows_the_project_and_its_language_switch(): void
    {
        $project = Project::factory()->create(['slug' => 'padel-mza', 'title' => ['es' => 'PádelMza', 'en' => 'PádelMza'], 'summary' => ['es' => 'App de pádel', 'en' => 'Padel app']]);

        $this->get('/proyectos/padel-mza')
            ->assertOk()
            ->assertSee('App de pádel')
            ->assertSee('<link rel="alternate" hreflang="en" href="'.url('/en/projects/padel-mza').'">', false);

        $this->get('/en/projects/padel-mza')->assertOk()->assertSee('Padel app');

        $project->update(['is_visible' => false]);
        $this->get('/proyectos/padel-mza')->assertNotFound();
    }

    public function test_sitemap_lists_pages_and_projects(): void
    {
        Project::factory()->create(['slug' => 'estudio-brillo']);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertSee(url('/en/services'), false)
            ->assertSee(url('/proyectos/estudio-brillo'), false);
    }
}
