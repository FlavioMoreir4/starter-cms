<?php

namespace App\Http\Resources;

use App\Domain\Content\Models\Category;
use App\Domain\Content\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string $slug
 * @property-read string|null $excerpt
 * @property-read string|null $body
 * @property-read string $status
 * @property-read Carbon|null $published_at
 * @property-read bool $is_featured
 * @property-read Category $category
 * @property-read Tag[] $tags
 * @property-read User $author
 */
class PostResource extends JsonResource
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
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'status' => $this->status,
            'published_at' => $this->published_at?->toISOString(),
            'is_featured' => $this->is_featured,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'author' => new UserResource($this->whenLoaded('author')),
        ];
    }
}
