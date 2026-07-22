<?php

namespace Database\Seeders;

use App\Domain\Content\Models\Tag;
use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'PHP', 'slug' => 'php'],
            ['name' => 'JavaScript', 'slug' => 'javascript'],
            ['name' => 'Vue.js', 'slug' => 'vuejs'],
            ['name' => 'React', 'slug' => 'react'],
            ['name' => 'CSS', 'slug' => 'css'],
            ['name' => 'HTML', 'slug' => 'html'],
            ['name' => 'MySQL', 'slug' => 'mysql'],
            ['name' => 'Docker', 'slug' => 'docker'],
            ['name' => 'Git', 'slug' => 'git'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => $tag['slug']],
                $tag
            );
        }
    }
}
