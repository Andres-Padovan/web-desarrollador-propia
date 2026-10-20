<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Artisan;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncGithub')
                ->label('Sincronizar GitHub')
                ->icon(Heroicon::ArrowPath)
                ->color('gray')
                ->action(function (): void {
                    $exitCode = Artisan::call('github:sync');

                    Notification::make()
                        ->title($exitCode === 0 ? 'GitHub sincronizado' : 'No se pudo sincronizar')
                        ->body(trim(Artisan::output()))
                        ->{$exitCode === 0 ? 'success' : 'danger'}()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
