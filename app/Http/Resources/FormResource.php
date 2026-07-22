<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $slug
 * @property-read array<string, mixed>|null $schema
 * @property-read string|null $success_message
 * @property-read string|null $redirect_url
 * @property-read bool $is_active
 */
class FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'schema' => $this->schema,
            'success_message' => $this->success_message,
            'redirect_url' => $this->redirect_url,
            'is_active' => $this->is_active,
        ];
    }
}
