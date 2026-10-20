<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $pages = [
            ['home', [], 1.0],
            ['projects', [], 0.9],
            ['services', [], 0.8],
            ['process', [], 0.7],
            ['about', [], 0.7],
            ...Project::published()->get()->map(fn (Project $project) => ['project', ['project' => $project->slug], 0.8]),
        ];

        return response()
            ->view('sitemap', ['pages' => $pages, 'lastModified' => now()->startOfDay()])
            ->header('Content-Type', 'application/xml');
    }
}
