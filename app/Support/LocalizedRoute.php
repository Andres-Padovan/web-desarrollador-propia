<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

/**
 * Las páginas existen en español (sin prefijo) y en inglés (/en). Cada ruta en inglés
 * se llama igual que la española con el sufijo ".en".
 */
class LocalizedRoute
{
    /**
     * URL de una página en el idioma actual.
     *
     * @param  array<string, mixed>|mixed  $parameters
     */
    public static function to(string $name, mixed $parameters = []): string
    {
        return route(app()->getLocale() === 'en' ? $name.'.en' : $name, $parameters);
    }

    /** URL de la página actual en el otro idioma (o en el pedido). */
    public static function alternate(?string $locale = null): string
    {
        $locale ??= app()->getLocale() === 'en' ? 'es' : 'en';
        $current = Route::current();
        $name = str_replace('.en', '', $current?->getName() ?? 'home');

        if (! Route::has($name)) {
            $name = 'home';
        }

        $parameters = collect($current?->parameters() ?? [])->except('locale')->all();

        return route($locale === 'en' ? $name.'.en' : $name, $name === 'home' ? [] : $parameters);
    }

    /** Si la página actual es la indicada, en cualquiera de los dos idiomas. */
    public static function is(string $name): bool
    {
        return request()->routeIs($name, $name.'.en');
    }
}
