<?php

use App\Domain\Forms\Models\Form;
use App\Domain\Forms\Models\FormSubmission;

it('displays an active form', function () {
    $form = Form::factory()->create(['is_active' => true]);

    $response = $this->get(route('forms.show', $form->slug));

    $response->assertOk();
});

it('returns 404 for inactive form', function () {
    $form = Form::factory()->inactive()->create();

    $response = $this->get(route('forms.show', $form->slug));

    $response->assertNotFound();
});

it('returns 404 for non-existent form', function () {
    $response = $this->get(route('forms.show', 'non-existent-form'));

    $response->assertNotFound();
});

it('submits a form and creates a submission', function () {
    $form = Form::factory()->create(['is_active' => true]);

    $response = $this->post(route('forms.submit', $form->slug), [
        'payload' => ['name' => 'John Doe', 'email' => 'john@example.com'],
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas(FormSubmission::class, [
        'form_id' => $form->id,
    ]);
});

it('validates payload is required on submit', function () {
    $form = Form::factory()->create(['is_active' => true]);

    $response = $this->post(route('forms.submit', $form->slug), []);

    $response->assertSessionHasErrors('payload');
});

it('redirects to redirect_url after submission when set', function () {
    $form = Form::factory()->create([
        'is_active' => true,
        'redirect_url' => '/thank-you',
    ]);

    $response = $this->post(route('forms.submit', $form->slug), [
        'payload' => ['name' => 'John Doe', 'email' => 'john@example.com'],
    ]);

    $response->assertRedirect('/thank-you');
});

it('returns 404 when submitting to inactive form', function () {
    $form = Form::factory()->inactive()->create();

    $response = $this->post(route('forms.submit', $form->slug), [
        'payload' => ['name' => 'John Doe'],
    ]);

    $response->assertNotFound();
});

it('stores IP and user agent in submission', function () {
    $form = Form::factory()->create(['is_active' => true]);

    $this->post(route('forms.submit', $form->slug), [
        'payload' => ['name' => 'Test'],
    ]);

    $submission = FormSubmission::where('form_id', $form->id)->first();

    expect($submission->ip)->not->toBeNull()
        ->and($submission->user_agent)->not->toBeNull();
});
