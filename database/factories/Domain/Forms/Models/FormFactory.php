<?php

namespace Database\Factories\Domain\Forms\Models;

use App\Domain\Forms\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Form>
 */
class FormFactory extends Factory
{
    protected $model = Form::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                        'title' => 'Name',
                    ],
                    'email' => [
                        'type' => 'string',
                        'title' => 'Email',
                        'format' => 'email',
                    ],
                ],
                'required' => ['name', 'email'],
            ],
            'notification_emails' => [fake()->email()],
            'success_message' => 'Thank you for your submission!',
            'redirect_url' => null,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
