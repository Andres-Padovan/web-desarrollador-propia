<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Filament\Support\Bilingual;
use App\Models\Project;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Contenido')
                    ->columnSpan(2)
                    ->schema([
                        Bilingual::tabs(fn (string $locale) => [
                            TextInput::make("title.{$locale}")->label('Título')->required($locale === 'es')->maxLength(120),
                            TextInput::make("category.{$locale}")->label('Categoría')->placeholder($locale === 'es' ? 'Sitio web, Sistema de gestión…' : 'Website, Management system…'),
                            Textarea::make("summary.{$locale}")->label('Resumen')->rows(4)->maxLength(400),
                        ]),
                        TagsInput::make('stack')->label('Tecnologías')->placeholder('Laravel, Vue…'),
                        FileUpload::make('image')->label('Captura o portada')->image()->disk('public')->directory('projects')->imageEditor()
                            ->helperText('Opcional. Si no subís imagen, se genera una portada estilo editor de código.'),
                        FileUpload::make('video')->label('Video de recorrido')->disk('public')->directory('projects/videos')
                            ->acceptedFileTypes(['video/mp4', 'video/webm'])->maxSize(20480)
                            ->helperText('Opcional. MP4 o WebM corto, sin sonido. Se reproduce al pasar el mouse por la tarjeta.'),
                    ]),
                Section::make('Publicación')
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('slug')->required()->unique(ignoreRecord: true)->helperText('Identificador en minúsculas, sin espacios.'),
                        Select::make('status')->label('Estado')->options(Project::STATUSES)->default('live')->required(),
                        Toggle::make('is_visible')->label('Visible en la web')->default(true),
                        Toggle::make('is_featured')->label('Destacado (aparece primero)'),
                        Toggle::make('is_private')->label('Repositorio privado')->helperText('Oculta el enlace al código.'),
                        TextInput::make('live_url')->label('URL del sitio')->url(),
                        TextInput::make('repo_url')->label('URL del repositorio')->url(),
                        ColorPicker::make('accent')->label('Color de acento'),
                        TextInput::make('language')->label('Lenguaje principal'),
                        TextInput::make('sort')->label('Orden')->numeric()->default(0),
                    ]),
            ]);
    }
}
