<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('title')->label('Proyecto')
                    ->state(fn (Project $record) => $record->localized('title', 'es'))
                    ->description(fn (Project $record) => $record->localized('category', 'es'))
                    ->searchable(query: fn (Builder $query, string $search) => $query->where('title->es', 'like', "%{$search}%")),
                TextColumn::make('status')->label('Estado')->badge()
                    ->formatStateUsing(fn (string $state) => Project::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => $state === 'live' ? 'success' : 'warning'),
                TextColumn::make('source')->label('Origen')->badge()->color('gray'),
                IconColumn::make('is_private')->label('Privado')->boolean(),
                ToggleColumn::make('is_featured')->label('Destacado'),
                ToggleColumn::make('is_visible')->label('Visible'),
                TextColumn::make('pushed_at')->label('Último push')->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_visible')->label('Visible'),
                SelectFilter::make('source')->label('Origen')->options(['manual' => 'Manual', 'github' => 'GitHub']),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
