<?php

namespace App\Domain\Pages\Actions\Blocks;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class HeroBlock extends Block
{
    public static function type(): string
    {
        return 'hero';
    }

    /**
     * @return array<int, Component>
     */
    public static function getFilamentSchema(): array
    {
        return [
            TextInput::make('title')
                ->label('Titulo')
                ->required(),
            TextInput::make('subtitle')
                ->label('Subtitulo'),
            FileUpload::make('background_image')
                ->label('Imagem de Fundo')
                ->image()
                ->disk('public')
                ->visibility('public')
                ->directory('directors')
                ->imageEditor()
                ->automaticallyOpenImageEditorForAspectRatio()
                ->automaticallyCropImagesToAspectRatio()
                ->automaticallyResizeImagesMode('cover')
                ->directory('pages/hero'),
            TextInput::make('button_text')
                ->label('Texto do Botao'),
            TextInput::make('button_url')
                ->label('URL do Botao'),
        ];
    }
}
