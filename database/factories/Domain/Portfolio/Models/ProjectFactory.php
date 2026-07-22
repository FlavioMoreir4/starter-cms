<?php

namespace Database\Factories\Domain\Portfolio\Models;

use App\Domain\Portfolio\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'body' => fake()->paragraphs(3, true),
            'client' => fake()->optional()->company(),
            'category' => fake()->optional()->word(),
            'repository_url' => fake()->optional()->url(),
            'demo_url' => fake()->optional()->url(),
            'status' => 'published',
            'is_featured' => false,
            'sort_order' => 0,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
