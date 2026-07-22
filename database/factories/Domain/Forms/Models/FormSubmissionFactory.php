<?php

namespace Database\Factories\Domain\Forms\Models;

use App\Domain\Forms\Models\Form;
use App\Domain\Forms\Models\FormSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FormSubmission>
 */
class FormSubmissionFactory extends Factory
{
    protected $model = FormSubmission::class;

    public function definition(): array
    {
        return [
            'form_id' => Form::factory(),
            'payload' => [
                'name' => fake()->name(),
                'email' => fake()->email(),
                'message' => fake()->paragraph(),
            ],
            'ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'status' => 'unread',
            'honeypot_value' => null,
        ];
    }
}
