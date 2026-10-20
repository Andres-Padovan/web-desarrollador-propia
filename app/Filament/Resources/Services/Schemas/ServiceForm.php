<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Support\Bilingual;
use App\Models\Service;
use Filament\Forms\Components\Repeater;
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
                    TextInput::make("title.{$locale}")->label('Nombre del paquete')->required($locale === 'es')->maxLength(80),
                    Textarea::make("description.{$locale}")->label('Descripción corta')->rows(3)->required($locale === 'es')->maxLength(300),
                    Textarea::make("details.{$locale}")->label('Detalle (se ve al tocar "Ver detalle")')->rows(4)->maxLength(800),
                    Repeater::make("includes.{$locale}")->label('Qué incluye')->simple(TextInput::make('item')->required()->maxLength(120))->reorderable()->defaultItems(0),
                    TextInput::make("delivery.{$locale}")->label('Plazo de entrega')->placeholder($locale === 'es' ? '2 a 3 semanas' : '2 to 3 weeks')->maxLength(60),
                ]),
                Select::make('group')->label('Pestaña')->options(Service::GROUPS)->default('sites')->required(),
                TagsInput::make('stack')->label('Tecnologías'),
                TextInput::make('icon')->label('Ícono')->helperText('Nombre de Heroicon, por ejemplo: globe-alt, squares-2x2, sparkles.'),
                TextInput::make('sort')->label('Orden')->numeric()->default(0),
                Toggle::make('is_highlighted')->label('Destacado ("el más pedido")'),
                Toggle::make('is_visible')->label('Visible')->default(true),
            ]);
    }
}
