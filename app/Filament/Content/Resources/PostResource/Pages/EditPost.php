<?php

namespace App\Filament\Content\Resources\PostResource\Pages;

use App\Domain\Content\Models\Post;
use App\Filament\Content\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    #[\Override]
    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['seo_title'], $data['seo_description'], $data['seo_image'], $data['featured_image']);

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var Post|null $record */
        $record = $this->record;

        if (! $record instanceof Post) {
            return;
        }

        $seoData = $this->data;

        if (isset($seoData['seo_title']) || isset($seoData['seo_description']) || isset($seoData['seo_image'])) {
            $record->seo()->updateOrCreate([], [
                'title' => $seoData['seo_title'] ?? null,
                'description' => $seoData['seo_description'] ?? null,
                'image' => $seoData['seo_image'] ?? null,
            ]);
        }

        if (isset($seoData['featured_image']) && is_string($seoData['featured_image'])) {
            $record->clearMediaCollection('featured_image');
            $record->addMediaFromDisk($seoData['featured_image'], 'public')
                ->toMediaCollection('featured_image');
        }
    }
}
