<?php

namespace App\Http\Controllers\Public;

use App\Domain\Content\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(Request $request): Response
    {
        $posts = Post::query()
            ->published()
            ->latest('published_at')
            ->with(['category', 'tags'])
            ->paginate($request->integer('per_page', 12));

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
        ]);
    }

    public function show(Post $post): Response
    {
        abort_unless($post->status === 'published', 404);

        $post->loadMissing(['category', 'tags', 'author']);

        return Inertia::render('Blog/Show', [
            'post' => $post,
        ]);
    }
}
