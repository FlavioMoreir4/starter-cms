<?php

use App\Domain\Content\Models\Category;
use App\Domain\Content\Models\Post;
use App\Domain\Content\Models\Tag;
use App\Models\User;

test('post belongs to a category', function () {
    $category = Category::factory()->create();
    $post = Post::factory()->create(['category_id' => $category->id]);

    expect($post->category)->toBeInstanceOf(Category::class)
        ->and($post->category->id)->toBe($category->id);
});

test('post belongs to an author', function () {
    $author = User::factory()->create();
    $post = Post::factory()->create(['author_id' => $author->id]);

    expect($post->author)->toBeInstanceOf(User::class)
        ->and($post->author->id)->toBe($author->id);
});

test('post belongs to many tags', function () {
    $post = Post::factory()->create();
    $tags = Tag::factory()->count(3)->create();
    $post->tags()->attach($tags->pluck('id'));

    expect($post->tags)->toHaveCount(3)
        ->and($post->tags->first())->toBeInstanceOf(Tag::class);
});

test('published scope filters by status and published_at', function () {
    Post::factory()->create([
        'status' => 'published',
        'published_at' => now()->subDay(),
    ]);
    Post::factory()->draft()->create([
        'published_at' => null,
    ]);
    Post::factory()->create([
        'status' => 'published',
        'published_at' => now()->addWeek(),
    ]);

    $published = Post::query()->published()->get();

    expect($published)->toHaveCount(1);
});

test('category has many posts', function () {
    $category = Category::factory()->create();
    Post::factory()->count(3)->create(['category_id' => $category->id]);

    expect($category->posts)->toHaveCount(3);
});

test('tag has many posts', function () {
    $tag = Tag::factory()->create();
    $posts = Post::factory()->count(2)->create();
    $tag->posts()->attach($posts->pluck('id'));

    expect($tag->posts)->toHaveCount(2);
});

test('post factory creates valid post with all relationships', function () {
    $post = Post::factory()->create();
    $tags = Tag::factory()->count(2)->create();
    $post->tags()->attach($tags->pluck('id'));

    expect($post)->toBeInstanceOf(Post::class)
        ->and($post->category)->not->toBeNull()
        ->and($post->author)->not->toBeNull()
        ->and($post->tags)->toHaveCount(2)
        ->and($post->title)->not->toBeEmpty()
        ->and($post->slug)->not->toBeEmpty()
        ->and($post->status)->toBe('published')
        ->and($post->published_at)->not->toBeNull();
});

test('draft post has null published_at', function () {
    $post = Post::factory()->draft()->create();

    expect($post->status)->toBe('draft')
        ->and($post->published_at)->toBeNull();
});

test('featured post has is_featured true', function () {
    $post = Post::factory()->featured()->create();

    expect($post->is_featured)->toBeTrue();
});
