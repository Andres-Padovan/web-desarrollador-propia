<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Mensajes sin leer', ContactMessage::whereNull('read_at')->count())
                ->description(ContactMessage::where('created_at', '>=', now()->subDays(30))->count().' en los últimos 30 días')
                ->color('primary'),
            Stat::make('Proyectos visibles', Project::where('is_visible', true)->count())
                ->description(Project::where('is_visible', false)->count().' ocultos esperando revisión'),
            Stat::make('Web pública', 'Ver sitio')
                ->url(route('home'), shouldOpenInNewTab: true)
                ->description('Español · /en en inglés'),
        ];
    }
}
