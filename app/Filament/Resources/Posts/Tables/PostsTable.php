<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                TextColumn::make('title')->label('Artículo')->state(fn (Post $record) => $record->localized('title', 'es'))->wrap(),
                TextColumn::make('category')->label('Tema')->formatStateUsing(fn (string $state) => Post::CATEGORIES[$state] ?? $state)->badge(),
                TextColumn::make('published_at')->label('Publicación')->dateTime('d/m/Y')->sortable(),
                ToggleColumn::make('is_published')->label('Publicado'),
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
