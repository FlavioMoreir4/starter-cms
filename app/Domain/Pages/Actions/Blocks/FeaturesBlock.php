<?php

namespace App\Domain\Pages\Actions\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class FeaturesBlock extends Block
{
    public static function type(): string
    {
        return 'features';
    }

    /**
     * @return array<int, Component>
     */
    public static function getFilamentSchema(): array
    {
        return [
            Repeater::make('items')
                ->label('Recursos')
                ->schema([
                    TextInput::make('icon')
                        ->label('Icone'),
                    TextInput::make('title')
                        ->label('Titulo')
                        ->required(),
                    Textarea::make('description')
                        ->label('Descricao'),
                ])
                ->columns(1)
                ->defaultItems(3)
                ->maxItems(6),
        ];
    }
}
