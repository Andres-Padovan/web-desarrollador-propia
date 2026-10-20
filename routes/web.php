<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\SetLocaleFromRoute;
use Illuminate\Support\Facades\Route;

/*
 * Cada página existe en español (sin prefijo) y en inglés (/en, nombre con ".en").
 * Los links se arman con App\Support\LocalizedRoute.
 */
$pages = [
    'es' => ['projects' => 'proyectos', 'services' => 'servicios', 'process' => 'proceso', 'about' => 'sobre-mi'],
    'en' => ['projects' => 'projects', 'services' => 'services', 'process' => 'process', 'about' => 'about'],
];

foreach ($pages as $locale => $paths) {
    $suffix = $locale === 'en' ? '.en' : '';

    Route::prefix($locale === 'en' ? 'en' : '')
        ->middleware(SetLocaleFromRoute::class)
        ->group(function () use ($locale, $paths, $suffix): void {
            Route::get('/', HomeController::class)->defaults('locale', $locale)->name('home'.$suffix);
            Route::get($paths['projects'], [PageController::class, 'projects'])->defaults('locale', $locale)->name('projects'.$suffix);
            Route::get($paths['projects'].'/{project:slug}', [PageController::class, 'project'])->defaults('locale', $locale)->name('project'.$suffix);
            Route::get($paths['services'], [PageController::class, 'services'])->defaults('locale', $locale)->name('services'.$suffix);
            Route::get($paths['process'], [PageController::class, 'process'])->defaults('locale', $locale)->name('process'.$suffix);
            Route::get($paths['about'], [PageController::class, 'about'])->defaults('locale', $locale)->name('about'.$suffix);
        });
}

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
