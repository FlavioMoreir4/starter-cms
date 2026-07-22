<?php

namespace App\Filament\Portfolio\Resources;

use App\Domain\Portfolio\Models\Project;
use App\Filament\Portfolio\Resources\Pages\CreateProject;
use App\Filament\Portfolio\Resources\Pages\EditProject;
use App\Filament\Portfolio\Resources\Pages\ListProjects;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Briefcase;

    protected static string|UnitEnum|null $navigationGroup = 'Portfólio';

    protected static ?string $navigationLabel = 'Projetos';

    protected static ?string $recordTitleAttribute = 'title';

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados do Projeto')
                    ->schema([
                        Grid::make(2)->schema([
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
                            TextInput::make('client')
                                ->label('Cliente'),
                            TextInput::make('category')
                                ->label('Categoria'),
                        ])->columnSpanFull(),
                        TagsInput::make('technologies')
                            ->label('Tecnologias')
                            ->placeholder('Adicionar tecnologia')
                            ->afterStateHydrated(function (Set $set, $record): void {
                                if ($record) {
                                    $set('technologies', $record->technologies->pluck('name')->toArray());
                                }
                            }),
                    ]),

                Section::make('Conteúdo')
                    ->schema([
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label('Conteúdo')
                            ->columnSpanFull(),
                    ]),

                Section::make('Links')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('repository_url')
                                ->url()
                                ->label('URL do Repositório'),
                            TextInput::make('demo_url')
                                ->url()
                                ->label('URL de Demo'),
                        ])->columnSpanFull(),
                    ]),

                Section::make('Mídia')
                    ->schema([
                        FileUpload::make('gallery')
                            ->multiple()
                            ->image()
                            ->disk('public')
                            ->directory('projects/gallery')
                            ->visibility('public')
                            ->panelLayout('integrated')
                            ->reorderable(),
                    ]),

                Section::make('SEO')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('seo_title')
                                ->label('Meta Title')
                                ->maxLength(60)
                                ->default(fn ($record) => $record?->seo?->title),
                            Textarea::make('seo_description')
                                ->label('Meta Description')
                                ->maxLength(160)
                                ->rows(2)
                                ->default(fn ($record) => $record?->seo?->description),
                        ])->columnSpanFull(),
                        FileUpload::make('seo_image')
                            ->label('OG Image')
                            ->image()
                            ->disk('public')
                            ->directory('seo')
                            ->visibility('public')
                            ->default(fn ($record) => $record?->seo?->image),
                    ]),

                Section::make('Configurações')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Rascunho',
                                    'published' => 'Publicado',
                                ])
                                ->default('draft')
                                ->required(),
                            Toggle::make('is_featured')
                                ->label('Destaque')
                                ->default(false),
                            TextInput::make('sort_order')
                                ->label('Ordem')
                                ->numeric()
                                ->default(0)
                                ->minValue(0),
                        ])->columnSpanFull(),
                    ]),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('client')
                    ->sortable()
                    ->searchable()
                    ->label('Cliente'),
                Tables\Columns\TextColumn::make('category')
                    ->sortable()
                    ->label('Categoria'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->sortable()
                    ->label('Destaque'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->label('Ordem'),
            ])
            ->defaultSort('sort_order')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->requiresConfirmation(),
            ]);
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}
