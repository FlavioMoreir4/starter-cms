<?php

namespace App\Domain\Pages\Actions\Blocks;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class ContactFormBlock extends Block
{
    public static function type(): string
    {
        return 'contact_form';
    }

    /**
     * @return array<int, Component>
     */
    public static function getFilamentSchema(): array
    {
        return [
            TextInput::make('title')
                ->label('Titulo'),
            Select::make('form_id')
                ->label('Formulario')
                ->relationship('form', 'name')
                ->searchable()
                ->preload(),
        ];
    }
}
