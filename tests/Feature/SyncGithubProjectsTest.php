<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SyncGithubProjectsTest extends TestCase
{
    use RefreshDatabase;

    private function repo(array $overrides = []): array
    {
        return [
            'id' => 101,
            'name' => 'sistema-turnos',
            'html_url' => 'https://github.com/Andres-Padovan/sistema-turnos',
            'description' => 'Agenda de turnos online',
            'language' => 'PHP',
            'stargazers_count' => 3,
            'private' => false,
            'fork' => false,
            'archived' => false,
            'homepage' => null,
            'topics' => ['laravel'],
            'pushed_at' => '2026-09-01T10:00:00Z',
            ...$overrides,
        ];
    }

    public function test_new_repositories_are_imported_hidden(): void
    {
        Http::fake(['api.github.com/*' => Http::response([$this->repo(), $this->repo(['id' => 102, 'name' => 'fork', 'fork' => true])])]);

        $this->artisan('github:sync')->assertSuccessful();

        $project = Project::sole();
        $this->assertSame('sistema-turnos', $project->slug);
        $this->assertSame('github', $project->source);
        $this->assertFalse($project->is_visible);
        $this->assertSame(['PHP', 'laravel'], $project->stack);
    }

    public function test_existing_projects_keep_hand_edited_texts_and_visibility(): void
    {
        Project::factory()->create(['github_id' => 101, 'slug' => 'sistema-turnos', 'title' => ['es' => 'Turnos Pro', 'en' => 'Turnos Pro'], 'is_visible' => true]);
        Http::fake(['api.github.com/*' => Http::response([$this->repo(['stargazers_count' => 9, 'private' => true])])]);

        $this->artisan('github:sync')->assertSuccessful();

        $project = Project::sole();
        $this->assertSame('Turnos Pro', $project->localized('title'));
        $this->assertTrue($project->is_visible);
        $this->assertTrue($project->is_private);
        $this->assertSame(9, $project->stars);
    }

    public function test_api_errors_fail_the_command(): void
    {
        Http::fake(['api.github.com/*' => Http::response(['message' => 'Bad credentials'], 401)]);

        $this->artisan('github:sync')->assertFailed();
    }
}
