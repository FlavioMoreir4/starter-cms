<?php

namespace App\Filament\Portfolio\Resources\Pages;

use App\Domain\Portfolio\Models\Project;
use App\Filament\Portfolio\Resources\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function afterCreate(): void
    {
        $this->syncTechnologies();
        $this->syncSeo();
        $this->syncGallery();
    }

    private function syncTechnologies(): void
    {
        /** @var Project|null $record */
        $record = $this->record;

        if (! $record instanceof Project) {
            return;
        }

        $technologies = $this->data['technologies'] ?? [];

        $record->technologies()->createMany(
            array_map(fn (string $name) => ['name' => $name], $technologies)
        );
    }

    private function syncSeo(): void
    {
        /** @var Project|null $record */
        $record = $this->record;

        if (! $record instanceof Project) {
            return;
        }

        $seoData = array_filter([
            'title' => $this->data['seo_title'] ?? null,
            'description' => $this->data['seo_description'] ?? null,
            'image' => $this->data['seo_image'] ?? null,
        ]);

        if (! empty($seoData)) {
            $record->seo()->create($seoData);
        }
    }

    private function syncGallery(): void
    {
        /** @var Project|null $record */
        $record = $this->record;

        if (! $record instanceof Project) {
            return;
        }

        $files = $this->data['gallery'] ?? [];

        foreach (array_filter($files) as $filePath) {
            $record
                ->addMediaFromDisk($filePath, 'public')
                ->toMediaCollection('gallery');
        }
    }
}
