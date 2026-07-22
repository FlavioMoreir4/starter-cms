<?php

namespace Database\Factories\Domain\Portfolio\Models;

use App\Domain\Portfolio\Models\Project;
use App\Domain\Portfolio\Models\Technology;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Technology>
 */
class TechnologyFactory extends Factory
{
    protected $model = Technology::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name' => fake()->randomElement([
                'PHP', 'Laravel', 'Vue.js', 'React', 'TypeScript',
                'Tailwind CSS', 'MySQL', 'PostgreSQL', 'Docker',
                'Node.js', 'Python', 'Go', 'Redis', 'Elasticsearch',
                'Filament', 'Livewire', 'Inertia.js', 'Vite',
            ]),
        ];
    }
}
