<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        return view('pages.blog', ['posts' => Post::published()->get()]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->isPublished(), 404);

        return view('pages.post', [
            'post' => $post,
            'others' => Post::published()->whereKeyNot($post->getKey())->limit(2)->get(),
        ]);
    }
}
