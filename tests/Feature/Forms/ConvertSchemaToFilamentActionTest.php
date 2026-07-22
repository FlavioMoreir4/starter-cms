<?php

use App\Domain\Forms\Actions\ConvertSchemaToFilamentAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

it('converts text input field', function () {
    $action = new ConvertSchemaToFilamentAction;
    $schema = [
        'properties' => [
            'name' => [
                'type' => 'string',
                'title' => 'Name',
            ],
        ],
    ];

    $components = $action->execute($schema);

    expect($components)->toHaveCount(1)
        ->and($components[0])->toBeInstanceOf(TextInput::class);
});

it('converts textarea field', function () {
    $action = new ConvertSchemaToFilamentAction;
    $schema = [
        'properties' => [
            'message' => [
                'type' => 'string',
                'title' => 'Message',
                'x-filament' => ['type' => 'textarea'],
            ],
        ],
    ];

    $components = $action->execute($schema);

    expect($components)->toHaveCount(1)
        ->and($components[0])->toBeInstanceOf(Textarea::class);
});

it('converts select field with options', function () {
    $action = new ConvertSchemaToFilamentAction;
    $schema = [
        'properties' => [
            'role' => [
                'type' => 'string',
                'title' => 'Role',
                'x-filament' => [
                    'type' => 'select',
                    'options' => ['admin' => 'Admin', 'user' => 'User'],
                ],
            ],
        ],
    ];

    $components = $action->execute($schema);

    expect($components)->toHaveCount(1)
        ->and($components[0])->toBeInstanceOf(Select::class);
});

it('converts checkbox field from boolean type', function () {
    $action = new ConvertSchemaToFilamentAction;
    $schema = [
        'properties' => [
            'active' => [
                'type' => 'boolean',
                'title' => 'Active',
            ],
        ],
    ];

    $components = $action->execute($schema);

    expect($components)->toHaveCount(1)
        ->and($components[0])->toBeInstanceOf(Checkbox::class);
});

it('converts checkbox field from filament type', function () {
    $action = new ConvertSchemaToFilamentAction;
    $schema = [
        'properties' => [
            'agree' => [
                'type' => 'string',
                'title' => 'I agree',
                'x-filament' => ['type' => 'checkbox'],
            ],
        ],
    ];

    $components = $action->execute($schema);

    expect($components)->toHaveCount(1)
        ->and($components[0])->toBeInstanceOf(Checkbox::class);
});

it('converts date field', function () {
    $action = new ConvertSchemaToFilamentAction;
    $schema = [
        'properties' => [
            'birth_date' => [
                'type' => 'string',
                'title' => 'Birth Date',
                'x-filament' => ['type' => 'date'],
            ],
        ],
    ];

    $components = $action->execute($schema);

    expect($components)->toHaveCount(1)
        ->and($components[0])->toBeInstanceOf(DatePicker::class);
});

it('converts number field from integer type', function () {
    $action = new ConvertSchemaToFilamentAction;
    $schema = [
        'properties' => [
            'age' => [
                'type' => 'integer',
                'title' => 'Age',
            ],
        ],
    ];

    $components = $action->execute($schema);

    expect($components)->toHaveCount(1)
        ->and($components[0])->toBeInstanceOf(TextInput::class);
});

it('converts radio field', function () {
    $action = new ConvertSchemaToFilamentAction;
    $schema = [
        'properties' => [
            'choice' => [
                'type' => 'string',
                'title' => 'Choice',
                'x-filament' => [
                    'type' => 'radio',
                    'options' => ['a' => 'Option A', 'b' => 'Option B'],
                ],
            ],
        ],
    ];

    $components = $action->execute($schema);

    expect($components)->toHaveCount(1)
        ->and($components[0])->toBeInstanceOf(Radio::class);
});

it('marks required fields as required', function () {
    $action = new ConvertSchemaToFilamentAction;
    $schema = [
        'properties' => [
            'email' => [
                'type' => 'string',
                'title' => 'Email',
            ],
        ],
        'required' => ['email'],
    ];

    $components = $action->execute($schema);

    expect($components)->toHaveCount(1)
        ->and($components[0]->isRequired())->toBeTrue();
});

it('does not mark non-required fields', function () {
    $action = new ConvertSchemaToFilamentAction;
    $schema = [
        'properties' => [
            'phone' => [
                'type' => 'string',
                'title' => 'Phone',
            ],
        ],
        'required' => [],
    ];

    $components = $action->execute($schema);

    expect($components)->toHaveCount(1)
        ->and($components[0]->isRequired())->toBeFalse();
});

it('handles empty schema with no properties', function () {
    $action = new ConvertSchemaToFilamentAction;
    $schema = [];

    $components = $action->execute($schema);

    expect($components)->toBeEmpty();
});

it('converts multiple fields at once', function () {
    $action = new ConvertSchemaToFilamentAction;
    $schema = [
        'properties' => [
            'name' => ['type' => 'string', 'title' => 'Name'],
            'email' => ['type' => 'string', 'title' => 'Email'],
            'bio' => ['type' => 'string', 'title' => 'Bio', 'x-filament' => ['type' => 'textarea']],
            'subscribe' => ['type' => 'boolean', 'title' => 'Subscribe'],
        ],
        'required' => ['name', 'email'],
    ];

    $components = $action->execute($schema);

    expect($components)->toHaveCount(4)
        ->and($components[0])->toBeInstanceOf(TextInput::class)
        ->and($components[1])->toBeInstanceOf(TextInput::class)
        ->and($components[2])->toBeInstanceOf(Textarea::class)
        ->and($components[3])->toBeInstanceOf(Checkbox::class);
});
