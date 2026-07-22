<?php

namespace Database\Seeders;

use App\Domain\Forms\Models\Form;
use Illuminate\Database\Seeder;

class FormsSeeder extends Seeder
{
    public function run(): void
    {
        $forms = [
            [
                'name' => 'Contato',
                'slug' => 'contato',
                'schema' => [
                    'fields' => [
                        ['name' => 'nome', 'type' => 'text', 'label' => 'Nome', 'required' => true],
                        ['name' => 'email', 'type' => 'email', 'label' => 'E-mail', 'required' => true],
                        ['name' => 'assunto', 'type' => 'text', 'label' => 'Assunto', 'required' => true],
                        ['name' => 'mensagem', 'type' => 'textarea', 'label' => 'Mensagem', 'required' => true],
                    ],
                ],
                'notification_emails' => ['admin@mktcode.digital'],
                'success_message' => 'Mensagem enviada com sucesso!',
                'redirect_url' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Newsletter',
                'slug' => 'newsletter',
                'schema' => [
                    'fields' => [
                        ['name' => 'email', 'type' => 'email', 'label' => 'E-mail', 'required' => true],
                    ],
                ],
                'notification_emails' => ['admin@mktcode.digital'],
                'success_message' => 'Inscrito na newsletter com sucesso!',
                'redirect_url' => null,
                'is_active' => true,
            ],
        ];

        foreach ($forms as $formData) {
            Form::firstOrCreate(
                ['slug' => $formData['slug']],
                $formData
            );
        }
    }
}
