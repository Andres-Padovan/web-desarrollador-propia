<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        return response()
            ->view('sitemap', ['lastModified' => now()->startOfDay()])
            ->header('Content-Type', 'application/xml');
    }
}
