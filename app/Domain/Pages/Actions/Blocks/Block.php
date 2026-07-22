<?php

namespace App\Domain\Pages\Actions\Blocks;

use Filament\Forms\Components\Builder\Block as FilamentBlock;
use Filament\Schemas\Components\Component;

abstract class Block
{
    /**
     * @return array<int, Component>
     */
    abstract public static function getFilamentSchema(): array;

    abstract public static function type(): string;

    public static function getLabel(): string
    {
        return class_basename(static::class);
    }

    public static function toFilamentBlock(): FilamentBlock
    {
        return FilamentBlock::make(static::type())
            ->label(static::getLabel())
            ->schema(static::getFilamentSchema());
    }
}
