<?php

namespace App\Domain\Pages\Actions\Blocks;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class TestimonialsBlock extends Block
{
    public static function type(): string
    {
        return 'testimonials';
    }

    /**
     * @return array<int, Component>
     */
    public static function getFilamentSchema(): array
    {
        return [
            Repeater::make('items')
                ->label('Depoimentos')
                ->schema([
                    Textarea::make('quote')
                        ->label('Depoimento')
                        ->required(),
                    TextInput::make('author_name')
                        ->label('Nome do Autor')
                        ->required(),
                    TextInput::make('author_role')
                        ->label('Cargo do Autor'),
                    FileUpload::make('avatar')
                        ->label('Avatar')
                        ->image()
                        ->disk('public')
                        ->visibility('public')
                        ->directory('directors')
                        ->imageEditor()
                        ->imageAspectRatio('1:1')
                        ->automaticallyOpenImageEditorForAspectRatio()
                        ->automaticallyCropImagesToAspectRatio()
                        ->automaticallyResizeImagesMode('cover')
                        ->directory('pages/testimonials'),
                ])
                ->columns(1)
                ->defaultItems(3),
        ];
    }
}
