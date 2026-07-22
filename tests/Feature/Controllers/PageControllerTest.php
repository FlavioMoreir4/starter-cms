<?php

use App\Domain\Pages\Models\Page;

it('displays a published page', function () {
    $page = Page::factory()->create(['status' => 'published']);

    $response = $this->get(route('pages.show', $page->slug));

    $response->assertOk();
});

it('returns 404 for draft page', function () {
    $page = Page::factory()->draft()->create();

    $response = $this->get(route('pages.show', $page->slug));

    $response->assertNotFound();
});

it('returns 404 for non-existent page', function () {
    $response = $this->get(route('pages.show', 'non-existent-page'));

    $response->assertNotFound();
});

it('displays page with content', function () {
    $page = Page::factory()->create([
        'status' => 'published',
        'content' => [
            ['type' => 'heading', 'data' => ['text' => 'Welcome']],
            ['type' => 'paragraph', 'data' => ['text' => 'Content here']],
        ],
    ]);

    $response = $this->get(route('pages.show', $page->slug));

    $response->assertOk();
});
