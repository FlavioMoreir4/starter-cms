<?php

namespace App\Domain\Forms\Models;

use Database\Factories\Domain\Forms\Models\FormFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'schema',
    'notification_emails',
    'success_message',
    'redirect_url',
    'is_active',
])]
class Form extends Model
{
    /** @use HasFactory<FormFactory> */
    use HasFactory;

    #[\Override]
    protected function casts(): array
    {
        return [
            'schema' => 'array',
            'notification_emails' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Summary of submissions
     *
     * @return HasMany<FormSubmission, $this>
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }
}
