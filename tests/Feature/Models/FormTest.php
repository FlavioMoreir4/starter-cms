<?php

use App\Domain\Forms\Models\Form;
use App\Domain\Forms\Models\FormSubmission;

test('form has many submissions', function () {
    $form = Form::factory()->create();
    FormSubmission::factory()->count(3)->create(['form_id' => $form->id]);

    expect($form->submissions)->toHaveCount(3)
        ->and($form->submissions->first())->toBeInstanceOf(FormSubmission::class);
});

test('submission belongs to a form', function () {
    $form = Form::factory()->create();
    $submission = FormSubmission::factory()->create(['form_id' => $form->id]);

    expect($submission->form)->toBeInstanceOf(Form::class)
        ->and($submission->form->id)->toBe($form->id);
});

test('payload is cast to array', function () {
    $submission = FormSubmission::factory()->create([
        'payload' => ['name' => 'John', 'email' => 'john@example.com'],
    ]);

    expect($submission->payload)->toBeArray()
        ->and($submission->payload['name'])->toBe('John')
        ->and($submission->payload['email'])->toBe('john@example.com');
});

test('schema is cast to array', function () {
    $form = Form::factory()->create([
        'schema' => [
            'type' => 'object',
            'properties' => ['name' => ['type' => 'string']],
        ],
    ]);

    expect($form->schema)->toBeArray()
        ->and($form->schema['type'])->toBe('object');
});

test('notification_emails is cast to array', function () {
    $form = Form::factory()->create([
        'notification_emails' => ['admin@example.com', 'info@example.com'],
    ]);

    expect($form->notification_emails)->toBeArray()
        ->and($form->notification_emails)->toHaveCount(2);
});

test('form factory creates valid form', function () {
    $form = Form::factory()->create();

    expect($form)->toBeInstanceOf(Form::class)
        ->and($form->name)->not->toBeEmpty()
        ->and($form->slug)->not->toBeEmpty()
        ->and($form->is_active)->toBeTrue()
        ->and($form->success_message)->not->toBeNull();
});

test('inactive form has is_active false', function () {
    $form = Form::factory()->inactive()->create();

    expect($form->is_active)->toBeFalse();
});
