<?php

namespace App\Http\Resources;

use App\Domain\Portfolio\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string $slug
 * @property-read string|null $description
 * @property-read string|null $body
 * @property-read string|null $client
 * @property-read string|null $category
 * @property-read string|null $repository_url
 * @property-read string|null $demo_url
 * @property-read string $status
 * @property-read bool $is_featured
 * @property-read int $sort_order
 * @property-read Technology[] $technologies
 */
class ProjectResource extends JsonResource
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
            'description' => $this->description,
            'body' => $this->body,
            'client' => $this->client,
            'category' => $this->category,
            'repository_url' => $this->repository_url,
            'demo_url' => $this->demo_url,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'sort_order' => $this->sort_order,
            'technologies' => TechnologyResource::collection($this->whenLoaded('technologies')),
        ];
    }
}
