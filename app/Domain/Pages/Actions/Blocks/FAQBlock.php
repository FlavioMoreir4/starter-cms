<?php

namespace App\Domain\Pages\Actions\Blocks;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class FAQBlock extends Block
{
    public static function type(): string
    {
        return 'faq';
    }

    /**
     * @return array<int, Component>
     */
    public static function getFilamentSchema(): array
    {
        return [
            Repeater::make('items')
                ->label('Perguntas Frequentes')
                ->schema([
                    TextInput::make('question')
                        ->label('Pergunta')
                        ->required(),
                    Textarea::make('answer')
                        ->label('Resposta')
                        ->required(),
                ])
                ->columns(1)
                ->defaultItems(5),
        ];
    }
}
