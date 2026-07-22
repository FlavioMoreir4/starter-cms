<?php

namespace App\Http\Controllers\Public;

use App\Domain\Content\Models\Post;
use App\Domain\Pages\Models\Page;
use App\Domain\Portfolio\Models\Project;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $homepage = Page::query()
            ->homepage()
            ->published()
            ->first();

        $featuredPosts = Post::query()
            ->published()
            ->where('is_featured', true)
            ->latest('published_at')
            ->limit(3)
            ->with(['category', 'author'])
            ->get();

        $featuredProjects = Project::query()
            ->published()
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->with('technologies')
            ->get();

        return Inertia::render('Home', [
            'homepage' => $homepage,
            'featuredPosts' => $featuredPosts,
            'featuredProjects' => $featuredProjects,
        ]);
    }
}
