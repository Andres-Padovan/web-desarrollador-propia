<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Cliente')->required()->helperText('Por privacidad podés usar nombre e inicial, por ejemplo "María G."'),
                TextInput::make('company')->label('Empresa'),
                Bilingual::tabs(fn (string $locale) => [
                    TextInput::make("project_type.{$locale}")->label('Tipo de proyecto'),
                    Textarea::make("quote.{$locale}")->label('Reseña')->rows(3)->required($locale === 'es'),
                ]),
                Select::make('rating')->label('Valoración')->options([5 => '★★★★★', 4 => '★★★★', 3 => '★★★'])->default(5),
                TextInput::make('sort')->label('Orden')->numeric()->default(0),
                Toggle::make('is_visible')->label('Visible')->default(true),
            ]);
    }
}
