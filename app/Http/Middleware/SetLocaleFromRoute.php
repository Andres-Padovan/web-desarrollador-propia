<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/** Cada ruta pública trae su idioma como valor por defecto ("es" o "en"). */
class SetLocaleFromRoute
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale($request->route('locale', 'es'));

        return $next($request);
    }
}
