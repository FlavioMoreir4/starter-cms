<?php

namespace App\Domain\Forms\Actions;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class ConvertSchemaToFilamentAction
{
    /**
     * @param  array<string, mixed>  $schema
     * @return array<int, Component>
     */
    public function execute(array $schema): array
    {
        $components = [];

        foreach ($schema['properties'] ?? [] as $name => $field) {
            $components[] = $this->makeComponent($name, $field, $schema);
        }

        return $components;
    }

    /**
     * @param  array<string, mixed>  $field
     * @param  array<string, mixed>  $schema
     */
    protected function makeComponent(string $name, array $field, array $schema): Component
    {
        $type = $field['x-filament']['type'] ?? $this->inferType($field);
        $required = in_array($name, $schema['required'] ?? []);
        $label = $field['title'] ?? $name;

        $component = match ($type) {
            'textarea' => Textarea::make($name)->label($label),
            'select' => Select::make($name)
                ->label($label)
                ->options($field['x-filament']['options'] ?? []),
            'checkbox' => Checkbox::make($name)->label($label),
            'date' => DatePicker::make($name)->label($label),
            'number' => TextInput::make($name)
                ->numeric()
                ->label($label),
            'radio' => Radio::make($name)
                ->label($label)
                ->options($field['x-filament']['options'] ?? []),
            default => TextInput::make($name)->label($label),
        };

        if ($required) {
            $component = $component->required();
        }

        if (isset($field['x-filament']['placeholder']) && method_exists($component, 'placeholder')) {
            $component = $component->placeholder($field['x-filament']['placeholder']);
        }

        if (isset($field['description'])) {
            $component = $component->helperText($field['description']);
        }

        return $component;
    }

    /**
     * @param  array<string, mixed>  $field
     */
    protected function inferType(array $field): string
    {
        return match ($field['type'] ?? 'string') {
            'string' => 'text',
            'number', 'integer' => 'number',
            'boolean' => 'checkbox',
            default => 'text',
        };
    }
}
