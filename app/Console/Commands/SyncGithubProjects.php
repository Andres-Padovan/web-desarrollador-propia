<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Imports the owner's GitHub repositories as projects. New repositories arrive hidden so they can be
 * curated in the panel first; existing ones only get their GitHub metadata refreshed, never the texts
 * or visibility edited by hand.
 */
class SyncGithubProjects extends Command
{
    protected $signature = 'github:sync {--publish-public : Publicar automáticamente los repos públicos nuevos}';

    protected $description = 'Importa y actualiza los repositorios de GitHub como proyectos';

    public function handle(): int
    {
        $user = config('profile.github_user');
        $token = config('services.github.token');

        $request = Http::acceptJson()->withHeaders(['X-GitHub-Api-Version' => '2022-11-28'])->timeout(20);

        // With a token, /user/repos also returns private repositories the token can see.
        $endpoint = $token ? 'https://api.github.com/user/repos' : "https://api.github.com/users/{$user}/repos";
        $query = $token ? ['affiliation' => 'owner', 'per_page' => 100, 'sort' => 'pushed'] : ['per_page' => 100, 'sort' => 'pushed'];

        $response = ($token ? $request->withToken($token) : $request)->get($endpoint, $query);

        if ($response->failed()) {
            $this->error("GitHub respondió {$response->status()}: ".$response->json('message', 'error desconocido'));

            return self::FAILURE;
        }

        $created = 0;
        $updated = 0;

        foreach ($response->json() as $repo) {
            if ($repo['fork'] ?? false) {
                continue;
            }

            $metadata = [
                'repo_url' => $repo['html_url'],
                'language' => $repo['language'],
                'stars' => $repo['stargazers_count'] ?? 0,
                'is_private' => (bool) $repo['private'],
                'pushed_at' => isset($repo['pushed_at']) ? Carbon::parse($repo['pushed_at']) : null,
                'live_url' => filled($repo['homepage'] ?? null) ? $repo['homepage'] : null,
            ];

            $project = Project::firstWhere('github_id', $repo['id'])
                ?? Project::firstWhere('slug', Str::slug($repo['name']));

            if ($project) {
                $project->update([...$metadata, 'github_id' => $repo['id'], 'live_url' => $project->live_url ?: $metadata['live_url']]);
                $updated++;

                continue;
            }

            $title = Str::headline($repo['name']);
            $summary = $repo['description'] ?? null;

            Project::create([
                ...$metadata,
                'github_id' => $repo['id'],
                'slug' => Str::slug($repo['name']),
                'title' => ['es' => $title, 'en' => $title],
                'summary' => $summary ? ['es' => $summary, 'en' => $summary] : null,
                'stack' => array_values(array_filter([$repo['language'], ...array_slice($repo['topics'] ?? [], 0, 3)])),
                'status' => ($repo['archived'] ?? false) ? 'archived' : 'live',
                'source' => 'github',
                'is_visible' => $this->option('publish-public') && ! $repo['private'],
                'sort' => 100,
            ]);
            $created++;
        }

        $this->info("Listo: {$created} proyectos nuevos (ocultos hasta que los publiques en /admin) y {$updated} actualizados.");

        return self::SUCCESS;
    }
}
