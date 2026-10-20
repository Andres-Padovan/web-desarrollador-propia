<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class PageController extends Controller
{
    public function projects(): View
    {
        $projects = Project::published()->get();

        return view('pages.projects', [
            'projects' => $projects,
            'categories' => $projects->map(fn (Project $project) => $project->localized('category'))->filter()->unique()->values(),
        ]);
    }

    public function project(Project $project): View
    {
        abort_unless($project->is_visible, 404);

        return view('pages.project', [
            'project' => $project,
            'others' => Project::published()->whereKeyNot($project->getKey())->limit(3)->get(),
        ]);
    }

    public function services(): View
    {
        return view('pages.services', [
            'groups' => Service::published()->get()->groupBy('group')->sortBy(fn ($services, string $group) => array_search($group, array_keys(Service::GROUPS))),
        ]);
    }

    public function process(): View
    {
        return view('pages.process');
    }

    public function about(): View
    {
        return view('pages.about', [
            'testimonials' => Testimonial::published()->get(),
            'projects' => Project::published()->get(),
        ]);
    }
}
