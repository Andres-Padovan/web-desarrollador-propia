<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    /** Portada corta: lo mejor de cada sección, con links a sus páginas. */
    public function __invoke(): View
    {
        $projects = Project::published()->get();

        return view('home', [
            'projects' => $projects->take(3),
            'projectsCount' => $projects->count(),
            'testimonials' => Testimonial::published()->get(),
        ]);
    }
}
