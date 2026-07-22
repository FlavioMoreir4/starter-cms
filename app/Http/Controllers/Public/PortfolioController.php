<?php

namespace App\Http\Controllers\Public;

use App\Domain\Portfolio\Models\Project;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function index(): Response
    {
        $projects = Project::query()
            ->published()
            ->orderBy('sort_order')
            ->with('technologies')
            ->get();

        return Inertia::render('Portfolio/Index', [
            'projects' => $projects,
        ]);
    }

    public function show(Project $project): Response
    {
        abort_unless($project->status === 'published', 404);

        $project->loadMissing('technologies');

        return Inertia::render('Portfolio/Show', [
            'project' => $project,
        ]);
    }
}
