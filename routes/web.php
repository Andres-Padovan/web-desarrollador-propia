<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->defaults('locale', 'es')->name('home');
Route::get('/en', HomeController::class)->defaults('locale', 'en')->name('home.en');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
