<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
            ['blog', [], 0.7],
            ...Project::published()->get()->map(fn (Project $project) => ['project', ['project' => $project->slug], 0.8]),
            ...Post::published()->get()->map(fn (Post $post) => ['post', ['post' => $post->slug], 0.6]),
        ];

        return response()
            ->view('sitemap', ['pages' => $pages, 'lastModified' => now()->startOfDay()])
            ->header('Content-Type', 'application/xml');
    }
}
