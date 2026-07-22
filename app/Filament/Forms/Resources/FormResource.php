<?php

namespace App\Filament\Forms\Resources;

use App\Domain\Forms\Models\Form;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class FormResource extends Resource
{
    protected static ?string $model = Form::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Formulários';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 40;

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make('Dados do Formulário')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                'slug',
                                Str::slug($state ?? ''),
                            )),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Toggle::make('is_active')
                            ->default(true),
                    ]),

                Section::make('Schema do Formulário')
                    ->schema([
                        Textarea::make('schema')
                            ->rows(20)
                            ->columnSpanFull()
                            ->placeholder('Cole aqui o JSON Schema do formulário...'),
                    ]),

                Section::make('Configurações')
                    ->schema([
                        Forms\Components\TagsInput::make('notification_emails')
                            ->label('E-mails de Notificação')
                            ->placeholder('Adicionar e-mail'),
                        Textarea::make('success_message')
                            ->rows(3)
                            ->label('Mensagem de Sucesso'),
                        TextInput::make('redirect_url')
                            ->label('URL de Redirecionamento')
                            ->url()
                            ->placeholder('https://'),
                    ]),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('submissions_count')
                    ->counts('submissions')
                    ->label('Submissões')
                    ->sortable(),
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
        return [
            FormResource\RelationManagers\SubmissionsRelationManager::class,
        ];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => FormResource\Pages\ListForms::route('/'),
            'create' => FormResource\Pages\CreateForm::route('/create'),
            'edit' => FormResource\Pages\EditForm::route('/{record}/edit'),
        ];
    }
}
