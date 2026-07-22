<?php

namespace App\Domain\Forms\Models;

use Database\Factories\Domain\Forms\Models\FormSubmissionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'form_id',
    'payload',
    'ip',
    'user_agent',
    'status',
    'honeypot_value',
])]
class FormSubmission extends Model
{
    /** @use HasFactory<FormSubmissionFactory> */
    use HasFactory;

    #[\Override]
    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    /**
     * Summary of form
     *
     * @return BelongsTo<Form, $this>
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
