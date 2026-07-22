<?php

namespace Database\Seeders;

use App\Domain\Content\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Tecnologia', 'slug' => 'tecnologia', 'description' => 'Artigos sobre tecnologia'],
            ['name' => 'Desenvolvimento', 'slug' => 'desenvolvimento', 'description' => 'Artigos sobre desenvolvimento'],
            ['name' => 'Design', 'slug' => 'design', 'description' => 'Artigos sobre design'],
            ['name' => 'Marketing', 'slug' => 'marketing', 'description' => 'Artigos sobre marketing'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
