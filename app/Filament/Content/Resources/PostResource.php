<?php

namespace App\Filament\Content\Resources;

use App\Domain\Content\Models\Post;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
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

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Conteúdo';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 5;

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make('Dados Principais')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                    'slug',
                                    Str::slug($state ?? ''),
                                ))
                                ->columnSpan(1),
                            TextInput::make('slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->columnSpan(1),
                            Select::make('category_id')
                                ->relationship('category', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->columnSpan(1),
                            Select::make('tags')
                                ->label('Tags')
                                ->multiple()
                                ->relationship('tags', 'name')
                                ->searchable()
                                ->preload()
                                ->columnSpan(1),
                            Toggle::make('is_featured')
                                ->label('Destaque')
                                ->default(false)
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Conteúdo')
                    ->schema([
                        Textarea::make('excerpt')
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->columnSpanFull(),
                    ]),

                Section::make('Publicação')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->options([
                                    'draft' => 'Rascunho',
                                    'published' => 'Publicado',
                                    'scheduled' => 'Agendado',
                                ])
                                ->required()
                                ->default('draft')
                                ->columnSpan(1),
                            DateTimePicker::make('published_at')
                                ->label('Data de Publicação')
                                ->columnSpan(1),
                        ]),
                    ]),

                Section::make('SEO')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('seo_title')
                                ->label('Título SEO')
                                ->maxLength(255)
                                ->columnSpan(1),
                            TextInput::make('seo_description')
                                ->label('Descrição SEO')
                                ->maxLength(500)
                                ->columnSpan(1),
                            FileUpload::make('seo_image')
                                ->label('Imagem SEO')
                                ->image()
                                ->disk('public')
                                ->directory('seo')
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Mídia')
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Imagem Destacada')
                            ->image()
                            ->disk('public')
                            ->directory('posts/featured')
                            ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'scheduled' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Rascunho',
                        'published' => 'Publicado',
                        'scheduled' => 'Agendado',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publicado em')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Autor')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Destaque'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => PostResource\Pages\ListPosts::route('/'),
            'create' => PostResource\Pages\CreatePost::route('/create'),
            'edit' => PostResource\Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
