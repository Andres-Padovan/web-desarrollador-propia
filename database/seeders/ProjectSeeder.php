<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * Solo carga proyectos reales. El resto se importa con `php artisan github:sync`
 * o se agrega desde el panel en /admin/projects.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::updateOrCreate(['slug' => 'web-desarrollador-propia'], [
            'title' => ['es' => 'Portfolio personal', 'en' => 'Personal portfolio'],
            'summary' => [
                'es' => 'Este sitio: terminal interactiva, español e inglés, panel de administración y proyectos sincronizados desde GitHub.',
                'en' => 'This site: interactive terminal, Spanish and English, admin panel and projects synced from GitHub.',
            ],
            'category' => ['es' => 'Sitio web', 'en' => 'Website'],
            'stack' => ['Laravel', 'Filament', 'Livewire', 'Tailwind'],
            'status' => 'live',
            'repo_url' => 'https://github.com/Andres-Padovan/web-desarrollador-propia',
            'accent' => '#ff3d7f',
            'language' => 'PHP',
            'is_private' => true,
            'is_featured' => true,
            'is_visible' => true,
            'sort' => 0,
        ]);
    }
}
