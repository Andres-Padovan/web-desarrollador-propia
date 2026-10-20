<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                IconColumn::make('read_at')->label('')->state(fn (ContactMessage $record) => $record->read_at === null)
                    ->boolean()->trueIcon(Heroicon::Envelope)->falseIcon(Heroicon::EnvelopeOpen)->trueColor('primary')->falseColor('gray'),
                TextColumn::make('name')->label('Nombre')->searchable()->weight('bold'),
                TextColumn::make('email')->label('Email')->searchable()->copyable(),
                TextColumn::make('budget')->label('Tipo')->badge(),
                TextColumn::make('message')->label('Mensaje')->limit(60),
                TextColumn::make('created_at')->label('Recibido')->since()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('read_at')->label('Leídos')->nullable(),
            ])
            ->recordActions([
                Action::make('whatsapp')->label('WhatsApp')->icon(Heroicon::ChatBubbleLeftRight)->color('success')
                    ->visible(fn (ContactMessage $record) => filled($record->phone))
                    ->url(fn (ContactMessage $record) => 'https://wa.me/'.preg_replace('/\D/', '', $record->phone), shouldOpenInNewTab: true),
                Action::make('reply')->label('Responder')->icon(Heroicon::ArrowUturnLeft)
                    ->url(fn (ContactMessage $record) => 'mailto:'.$record->email.'?subject='.rawurlencode('Re: tu consulta')),
                EditAction::make()->label('Ver'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
