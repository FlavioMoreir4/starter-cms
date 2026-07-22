<?php

namespace App\Filament\Content\Resources\PostResource\Pages;

use App\Domain\Content\Models\Post;
use App\Filament\Content\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    #[\Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['author_id'] = Auth::id();

        unset($data['seo_title'], $data['seo_description'], $data['seo_image'], $data['featured_image']);

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Post|null $record */
        $record = $this->record;

        if (! $record instanceof Post) {
            return;
        }

        $seoData = $this->data;

        if (isset($seoData['seo_title']) || isset($seoData['seo_description']) || isset($seoData['seo_image'])) {
            $record->seo()->create([
                'title' => $seoData['seo_title'] ?? null,
                'description' => $seoData['seo_description'] ?? null,
                'image' => $seoData['seo_image'] ?? null,
            ]);
        }

        if (isset($seoData['featured_image']) && is_string($seoData['featured_image'])) {
            $record->addMediaFromDisk($seoData['featured_image'], 'public')
                ->toMediaCollection('featured_image');
        }
    }
}
