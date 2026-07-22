<?php

use App\Domain\Pages\Models\Page;

test('published scope filters by status', function () {
    Page::factory()->count(2)->create(['status' => 'published']);
    Page::factory()->draft()->create();

    $published = Page::query()->published()->get();

    expect($published)->toHaveCount(2);
});

test('homepage scope filters by is_homepage', function () {
    Page::factory()->homepage()->create();
    Page::factory()->count(2)->create();

    $homepages = Page::query()->homepage()->get();

    expect($homepages)->toHaveCount(1)
        ->and($homepages->first()->is_homepage)->toBeTrue();
});

test('content is cast to array', function () {
    $page = Page::factory()->create([
        'content' => [
            ['type' => 'heading', 'data' => ['text' => 'Hello']],
            ['type' => 'paragraph', 'data' => ['text' => 'World']],
        ],
    ]);

    expect($page->content)->toBeArray()
        ->and($page->content)->toHaveCount(2)
        ->and($page->content[0]['type'])->toBe('heading');
});

test('page factory creates valid page', function () {
    $page = Page::factory()->create();

    expect($page)->toBeInstanceOf(Page::class)
        ->and($page->title)->not->toBeEmpty()
        ->and($page->slug)->not->toBeEmpty()
        ->and($page->status)->toBe('published')
        ->and($page->template)->toBe('default')
        ->and($page->is_homepage)->toBeFalse();
});

test('homepage page has is_homepage true', function () {
    $page = Page::factory()->homepage()->create();

    expect($page->is_homepage)->toBeTrue();
});

test('draft page has draft status', function () {
    $page = Page::factory()->draft()->create();

    expect($page->status)->toBe('draft');
});
