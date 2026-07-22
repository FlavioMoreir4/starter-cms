<?php

use App\Domain\Portfolio\Models\Project;
use App\Domain\Portfolio\Models\Technology;

test('project has many technologies', function () {
    $project = Project::factory()->create();
    collect(['PHP', 'Laravel', 'Vue.js'])->each(
        fn ($name) => Technology::create(['project_id' => $project->id, 'name' => $name])
    );

    expect($project->technologies)->toHaveCount(3)
        ->and($project->technologies->first())->toBeInstanceOf(Technology::class);
});

test('technology belongs to a project', function () {
    $project = Project::factory()->create();
    $technology = Technology::create([
        'project_id' => $project->id,
        'name' => 'Laravel',
    ]);

    expect($technology->project)->toBeInstanceOf(Project::class)
        ->and($technology->project->id)->toBe($project->id);
});

test('published scope filters by status', function () {
    Project::factory()->count(2)->create(['status' => 'published']);
    Project::factory()->draft()->create();

    $published = Project::query()->published()->get();

    expect($published)->toHaveCount(2);
});

test('project factory creates valid project', function () {
    $project = Project::factory()->create();

    expect($project)->toBeInstanceOf(Project::class)
        ->and($project->title)->not->toBeEmpty()
        ->and($project->slug)->not->toBeEmpty()
        ->and($project->status)->toBe('published');
});

test('draft project has draft status', function () {
    $project = Project::factory()->draft()->create();

    expect($project->status)->toBe('draft');
});

test('featured project has is_featured true', function () {
    $project = Project::factory()->featured()->create();

    expect($project->is_featured)->toBeTrue();
});

test('technology creates with valid data', function () {
    $project = Project::factory()->create();
    $technology = Technology::create([
        'project_id' => $project->id,
        'name' => 'Laravel',
    ]);

    expect($technology)->toBeInstanceOf(Technology::class)
        ->and($technology->name)->toBe('Laravel')
        ->and($technology->project_id)->toBe($project->id);
});
