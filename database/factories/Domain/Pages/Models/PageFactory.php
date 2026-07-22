<?php

namespace Database\Factories\Domain\Pages\Models;

use App\Domain\Pages\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'template' => 'default',
            'content' => null,
            'status' => 'published',
            'is_homepage' => false,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function homepage(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_homepage' => true,
        ]);
    }
}
