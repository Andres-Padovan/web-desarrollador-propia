<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Nombre')->disabled(),
                TextInput::make('email')->label('Email')->disabled(),
                TextInput::make('phone')->label('Teléfono')->disabled(),
                TextInput::make('budget')->label('Tipo de proyecto')->disabled(),
                Textarea::make('message')->label('Mensaje')->rows(8)->disabled()->columnSpanFull(),
            ]);
    }
}
