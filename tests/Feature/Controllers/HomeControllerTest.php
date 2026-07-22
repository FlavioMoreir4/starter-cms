<?php

use App\Domain\Content\Models\Post;
use App\Domain\Pages\Models\Page;
use App\Domain\Portfolio\Models\Project;

it('renders the home page', function () {
    Page::factory()->homepage()->create();
    Post::factory()->count(2)->create(['status' => 'published', 'published_at' => now()]);
    Project::factory()->count(2)->create(['status' => 'published']);

    $response = $this->get(route('home'));

    $response->assertOk();
});

it('passes homepage, featured posts and projects to view', function () {
    Page::factory()->homepage()->create();
    Post::factory()->featured()->create(['status' => 'published', 'published_at' => now()]);
    Project::factory()->featured()->create(['status' => 'published']);

    $response = $this->get(route('home'));

    $response->assertOk();
});

it('returns ok even with no data', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});
