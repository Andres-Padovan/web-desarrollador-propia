<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Support\Bilingual;
use App\Models\Post;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Artículo')
                    ->columnSpan(2)
                    ->schema([
                        Bilingual::tabs(fn (string $locale) => [
                            TextInput::make("title.{$locale}")->label('Título')->required($locale === 'es')->maxLength(140),
                            Textarea::make("excerpt.{$locale}")->label('Bajada')->rows(2)->required($locale === 'es')->maxLength(220)
                                ->helperText('Una o dos oraciones. Aparece en la tarjeta del blog y en Google.'),
                            MarkdownEditor::make("body.{$locale}")->label('Texto')->required($locale === 'es')
                                ->toolbarButtons([['bold', 'italic', 'link'], ['heading', 'bulletList', 'orderedList', 'blockquote'], ['undo', 'redo']]),
                        ]),
                    ]),
                Section::make('Publicación')
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('slug')->required()->unique(ignoreRecord: true)->helperText('Parte final de la dirección, en minúsculas y con guiones.'),
                        Select::make('category')->label('Tema')->options(Post::CATEGORIES)->default('web')->required(),
                        DateTimePicker::make('published_at')->label('Fecha de publicación')->default(now())->required()
                            ->helperText('Si ponés una fecha futura, el artículo aparece ese día.'),
                        Toggle::make('is_published')->label('Publicado')->default(true),
                    ]),
            ]);
    }
}
