<?php

namespace App\Domain\Pages\Actions\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class FooterBlock extends Block
{
    public static function type(): string
    {
        return 'footer';
    }

    /**
     * @return array<int, Component>
     */
    public static function getFilamentSchema(): array
    {
        return [
            Repeater::make('columns')
                ->label('Colunas')
                ->schema([
                    TextInput::make('title')
                        ->label('Titulo da Coluna')
                        ->required(),
                    Repeater::make('links')
                        ->label('Links')
                        ->schema([
                            TextInput::make('label')
                                ->label('Texto do Link')
                                ->required(),
                            TextInput::make('url')
                                ->label('URL')
                                ->required(),
                        ])
                        ->columns(2)
                        ->defaultItems(3),
                ])
                ->columns(1)
                ->defaultItems(3)
                ->maxItems(5),
        ];
    }
}
