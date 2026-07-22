<?php

namespace App\Filament\Resources;

use App\Domain\Pages\Models\Page;
use App\Filament\Resources\Pages\CreatePage;
use App\Filament\Resources\Pages\EditPage;
use App\Filament\Resources\Pages\ListPages;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Document;

    protected static string|UnitEnum|null $navigationGroup = 'Páginas';

    protected static ?string $recordTitleAttribute = 'title';

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form->components([
            Section::make('Dados da Página')
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                            'slug',
                            Str::slug($state ?? ''),
                        )),
                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),
                    Select::make('template')
                        ->options([
                            'default' => 'Default',
                            'about' => 'About',
                            'contact' => 'Contact',
                        ])
                        ->default('default')
                        ->required(),
                    Select::make('status')
                        ->options([
                            'draft' => 'Rascunho',
                            'published' => 'Publicado',
                        ])
                        ->default('draft')
                        ->required(),
                    Toggle::make('is_homepage')
                        ->default(false),
                ]),
            Section::make('Blocos de Conteúdo')
                ->schema([
                    Builder::make('content')
                        ->blocks([]),
                ]),
            Section::make('SEO')
                ->relationship('seo')
                ->schema([
                    TextInput::make('title')
                        ->label('SEO Title'),
                    Textarea::make('description')
                        ->label('SEO Description'),
                    FileUpload::make('image')
                        ->label('SEO Image')
                        ->image(),
                ]),
        ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('template')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        default => 'gray',
                    }),
                IconColumn::make('is_homepage')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])

            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
