<?php

namespace App\Domain\Pages\Actions\Blocks;

use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Component;

class RichTextBlock extends Block
{
    public static function type(): string
    {
        return 'rich_text';
    }

    /**
     * @return array<int, Component>
     */
    public static function getFilamentSchema(): array
    {
        return [
            RichEditor::make('content')
                ->label('Conteudo')
                ->required(),
        ];
    }
}
