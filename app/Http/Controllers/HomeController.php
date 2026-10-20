<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request, string $locale): View
    {
        App::setLocale($locale);

        $projects = Project::published()->get();

        return view('home', [
            'services' => Service::published()->get(),
            'projects' => $projects,
            'categories' => $projects->map(fn (Project $project) => $project->localized('category'))->filter()->unique()->values(),
            'testimonials' => Testimonial::published()->get(),
        ]);
    }
}
