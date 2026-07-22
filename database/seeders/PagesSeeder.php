<?php

namespace Database\Seeders;

use App\Domain\Pages\Models\Page;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Sobre',
                'slug' => 'sobre',
                'template' => 'about',
                'content' => [
                    ['type' => 'hero', 'data' => ['title' => 'Sobre Nós', 'subtitle' => 'Conheça nossa história']],
                    ['type' => 'rich_text', 'data' => ['content' => '<p>Somos uma empresa de tecnologia...</p>']],
                ],
                'status' => 'published',
                'is_homepage' => false,
            ],
            [
                'title' => 'Contato',
                'slug' => 'contato',
                'template' => 'contact',
                'content' => [
                    ['type' => 'hero', 'data' => ['title' => 'Contato', 'subtitle' => 'Fale conosco']],
                    ['type' => 'contact_form', 'data' => ['form_id' => null]],
                ],
                'status' => 'published',
                'is_homepage' => false,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::firstOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
