<?php

namespace App\Domain\Pages\Actions\Blocks;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class CTABlock extends Block
{
    public static function type(): string
    {
        return 'cta';
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
            Textarea::make('description')
                ->label('Descricao'),
            TextInput::make('button_text')
                ->label('Texto do Botao')
                ->required(),
            TextInput::make('button_url')
                ->label('URL do Botao')
                ->required(),
            TextInput::make('background_color')
                ->label('Cor de Fundo')
                ->placeholder('#1a1a1a'),
        ];
    }
}
