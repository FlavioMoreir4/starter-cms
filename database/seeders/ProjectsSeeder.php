<?php

namespace Database\Seeders;

use App\Domain\Portfolio\Models\Project;
use App\Domain\Portfolio\Models\Technology;
use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'E-commerce Platform',
                'slug' => 'ecommerce-platform',
                'description' => 'Plataforma completa de e-commerce',
                'body' => '<h2>E-commerce</h2><p>Sistema de e-commerce com Laravel e Vue.js</p>',
                'client' => 'TechCorp',
                'category' => 'Web Application',
                'repository_url' => null,
                'demo_url' => null,
                'status' => 'published',
                'is_featured' => true,
                'sort_order' => 1,
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Docker'],
            ],
            [
                'title' => 'Task Management App',
                'slug' => 'task-management-app',
                'description' => 'Aplicativo de gerenciamento de tarefas',
                'body' => '<h2>Task Manager</h2><p>App para gerenciar projetos e tarefas</p>',
                'client' => null,
                'category' => 'Productivity',
                'repository_url' => null,
                'demo_url' => null,
                'status' => 'published',
                'is_featured' => false,
                'sort_order' => 2,
                'technologies' => ['Laravel', 'Livewire', 'MySQL'],
            ],
            [
                'title' => 'Portfolio Website',
                'slug' => 'portfolio-website',
                'description' => 'Site portfolio profissional',
                'body' => '<h2>Portfolio</h2><p>Site portfolio com blog integrado</p>',
                'client' => null,
                'category' => 'Website',
                'repository_url' => null,
                'demo_url' => null,
                'status' => 'published',
                'is_featured' => false,
                'sort_order' => 3,
                'technologies' => ['Laravel', 'Inertia.js', 'Vue.js', 'Tailwind CSS'],
            ],
        ];

        foreach ($projects as $projectData) {
            $technologies = $projectData['technologies'] ?? [];
            unset($projectData['technologies']);

            $project = Project::firstOrCreate(
                ['slug' => $projectData['slug']],
                $projectData
            );

            foreach ($technologies as $techName) {
                Technology::firstOrCreate(
                    ['project_id' => $project->id, 'name' => $techName],
                    ['project_id' => $project->id, 'name' => $techName]
                );
            }
        }
    }
}
