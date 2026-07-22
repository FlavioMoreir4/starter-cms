<?php

namespace App\Http\Controllers\Public;

use App\Domain\Forms\Models\Form;
use App\Domain\Forms\Models\FormSubmission;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FormController extends Controller
{
    public function show(Form $form): Response
    {
        abort_unless($form->is_active, 404);

        return Inertia::render('Forms/Show', [
            'form' => $form,
        ]);
    }

    public function submit(Request $request, Form $form): RedirectResponse
    {
        abort_unless($form->is_active, 404);

        $validated = $request->validate([
            'payload' => 'required|array',
            'honeypot' => 'nullable|string|max:0',
        ]);

        FormSubmission::create([
            'form_id' => $form->id,
            'payload' => $validated['payload'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($form->redirect_url) {
            return redirect($form->redirect_url);
        }

        return back()->with('success', $form->success_message ?? 'Form submitted successfully.');
    }
}
