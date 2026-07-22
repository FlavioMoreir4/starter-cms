<?php

use App\Domain\Portfolio\Models\Project;
use App\Domain\Portfolio\Models\Technology;

it('displays the portfolio index page', function () {
    Project::factory()->count(3)->create(['status' => 'published']);

    $response = $this->get(route('portfolio.index'));

    $response->assertOk();
});

it('displays a published project', function () {
    $project = Project::factory()->create(['status' => 'published']);

    $response = $this->get(route('portfolio.show', $project->slug));

    $response->assertOk();
});

it('returns 404 for draft project', function () {
    $project = Project::factory()->draft()->create();

    $response = $this->get(route('portfolio.show', $project->slug));

    $response->assertNotFound();
});

it('returns 404 for non-existent project', function () {
    $response = $this->get(route('portfolio.show', 'non-existent-slug'));

    $response->assertNotFound();
});

it('loads technologies for published projects', function () {
    $project = Project::factory()->create(['status' => 'published']);
    collect(['Laravel', 'Vue.js'])->each(
        fn ($name) => Technology::create(['project_id' => $project->id, 'name' => $name])
    );

    $response = $this->get(route('portfolio.show', $project->slug));

    $response->assertOk();
});

it('only shows published projects on index', function () {
    Project::factory()->count(3)->create(['status' => 'published']);
    Project::factory()->count(2)->draft()->create();

    $response = $this->get(route('portfolio.index'));

    $response->assertOk();
});
