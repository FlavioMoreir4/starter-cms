<?php

use App\Domain\Content\Models\Post;

it('displays the blog index page', function () {
    Post::factory()->count(5)->create(['status' => 'published', 'published_at' => now()]);

    $response = $this->get(route('blog.index'));

    $response->assertOk();
});

it('displays a published post', function () {
    $post = Post::factory()->create([
        'status' => 'published',
        'published_at' => now(),
    ]);

    $response = $this->get(route('blog.show', $post->slug));

    $response->assertOk();
});

it('returns 404 for draft post', function () {
    $post = Post::factory()->draft()->create();

    $response = $this->get(route('blog.show', $post->slug));

    $response->assertNotFound();
});

it('returns 404 for non-existent post', function () {
    $response = $this->get(route('blog.show', 'non-existent-slug'));

    $response->assertNotFound();
});

it('only shows published posts on index', function () {
    Post::factory()->count(3)->create(['status' => 'published', 'published_at' => now()]);
    Post::factory()->count(2)->draft()->create();

    $response = $this->get(route('blog.index'));

    $response->assertOk();
});
