<?php

namespace Database\Seeders;

use App\Domain\Content\Models\Category;
use App\Domain\Content\Models\Post;
use App\Domain\Content\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $tags = Tag::all();
        $author = User::where('email', 'admin@mktcode.digital')->firstOrFail();

        $posts = [
            [
                'title' => 'Introdução ao Laravel 13',
                'slug' => 'introducao-ao-laravel-13',
                'excerpt' => 'Descubra as novidades do Laravel 13',
                'body' => '<h2>Laravel 13</h2><p>O Laravel 13 traz muitas novidades...</p>',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Filament v5: O que há de novo',
                'slug' => 'filament-v5-o-que-ha-de-novo',
                'excerpt' => 'Conheça as novidades do Filament v5',
                'body' => '<h2>Filament v5</h2><p>O Filament v5 revolutioniza...</p>',
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Inertia.js com Vue 3',
                'slug' => 'inertia-js-com-vue-3',
                'excerpt' => 'Como usar Inertia.js com Vue 3',
                'body' => '<h2>Inertia.js</h2><p>O Inertia.js facilita...</p>',
                'status' => 'published',
                'published_at' => now()->subDay(),
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::firstOrCreate(
                ['slug' => $postData['slug']],
                [
                    ...$postData,
                    'category_id' => $categories->random()->id,
                    'author_id' => $author->id,
                ]
            );

            if ($tags->isNotEmpty()) {
                $post->tags()->sync($tags->random(min(3, $tags->count()))->pluck('id'));
            }
        }
    }
}
