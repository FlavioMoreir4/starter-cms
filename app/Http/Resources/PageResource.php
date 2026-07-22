<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string $slug
 * @property-read string $template
 * @property-read array<string, mixed>|null $content
 * @property-read string $status
 * @property-read bool $is_homepage
 */
class PageResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'template' => $this->template,
            'content' => $this->content,
            'status' => $this->status,
            'is_homepage' => $this->is_homepage,
        ];
    }
}
