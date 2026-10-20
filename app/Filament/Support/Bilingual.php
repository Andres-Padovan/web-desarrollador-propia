<?php

namespace App\Filament\Support;

use Closure;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * Renders the same fields once per site language. Each field closure receives the locale
 * and should name its field "attribute.{locale}" so it lands in the model's JSON column.
 */
class Bilingual
{
    public const LOCALES = ['es' => 'Español', 'en' => 'English'];

    /** @param  Closure(string $locale): array<int, mixed>  $fields */
    public static function tabs(Closure $fields): Tabs
    {
        return Tabs::make('Idiomas')
            ->tabs(collect(self::LOCALES)
                ->map(fn (string $label, string $locale) => Tab::make($label)->schema($fields($locale)))
                ->values()
                ->all())
            ->columnSpanFull();
    }
}
