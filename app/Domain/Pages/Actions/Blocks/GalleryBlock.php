<?php

namespace App\Domain\Pages\Actions\Blocks;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Component;

class GalleryBlock extends Block
{
    public static function type(): string
    {
        return 'gallery';
    }

    /**
     * @return array<int, Component>
     */
    public static function getFilamentSchema(): array
    {
        return [
            FileUpload::make('images')
                ->label('Imagens')
                ->multiple()
                ->image()
                ->disk('public')
                ->visibility('public')
                ->directory('directors')
                ->imageEditor()
                ->automaticallyOpenImageEditorForAspectRatio()
                ->automaticallyCropImagesToAspectRatio()
                ->automaticallyResizeImagesMode('cover')
                ->directory('pages/gallery'),
            Textarea::make('caption')
                ->label('Legenda'),
        ];
    }
}
