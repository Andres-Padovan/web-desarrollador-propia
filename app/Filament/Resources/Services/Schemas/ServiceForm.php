<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Bilingual::tabs(fn (string $locale) => [
                    TextInput::make("title.{$locale}")->label('Título')->required($locale === 'es')->maxLength(80),
                    Textarea::make("description.{$locale}")->label('Descripción')->rows(4)->required($locale === 'es')->maxLength(400),
                ]),
                TagsInput::make('stack')->label('Tecnologías'),
                TextInput::make('icon')->label('Ícono')->helperText('Nombre de Heroicon, por ejemplo: globe-alt, squares-2x2, arrows-right-left.'),
                Select::make('span')->label('Ancho de la tarjeta')->options([1 => '1 columna', 2 => '2 columnas', 3 => 'Ancho completo'])->default(1)->required(),
                TextInput::make('sort')->label('Orden')->numeric()->default(0),
                Toggle::make('is_visible')->label('Visible')->default(true),
            ]);
    }
}
