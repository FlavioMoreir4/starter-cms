<?php

namespace App\Http\Controllers\Public;

use App\Domain\Pages\Models\Page;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function show(Page $page): Response
    {
        abort_unless($page->status === 'published', 404);

        return Inertia::render('Page', [
            'page' => $page,
        ]);
    }
}
