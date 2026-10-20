<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

/**
 * With "npm run dev" running, pages load CSS and JS from the dev server on localhost. A phone opening the
 * site through a tunnel (cloudflared) or the local network can't reach it and sees the page without styles,
 * so those visits get the compiled files from "npm run build" instead.
 */
class ServeBuiltAssetsToRemoteVisitors
{
    /** Hosts where the Vite dev server is reachable. */
    private const LOCAL_HOSTS = ['localhost', '127.0.0.1', '::1'];

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isRemoteVisit($request) && is_file(public_path('hot')) && is_file(public_path('build/manifest.json'))) {
            Vite::useHotFile(storage_path('framework/vite-hot-disabled'));
        }

        return $next($request);
    }

    private function isRemoteVisit(Request $request): bool
    {
        $host = $request->getHost();

        return $request->hasHeader('CF-Connecting-IP')
            || ! (in_array($host, self::LOCAL_HOSTS, true) || str_ends_with($host, '.test'));
    }
}
