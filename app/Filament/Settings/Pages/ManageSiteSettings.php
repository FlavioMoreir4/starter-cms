<?php

namespace App\Filament\Settings\Pages;

use App\Domain\Settings\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageSiteSettings extends SettingsPage
{
    protected static string $settings = SiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog6Tooth;

    protected static ?string $navigationLabel = 'Site Settings';

    #[\Override]
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identidade Visual')->schema([
                TextInput::make('site_name')
                    ->required(),
                FileUpload::make('logo')
                    ->disk('public')
                    ->visibility('public')
                    ->directory('directors')
                    ->imageEditor()
                    ->imageAspectRatio('1:1')
                    ->automaticallyOpenImageEditorForAspectRatio()
                    ->automaticallyCropImagesToAspectRatio()
                    ->automaticallyResizeImagesMode('cover')
                    ->image(),
                FileUpload::make('favicon')
                    ->disk('public')
                    ->visibility('public')
                    ->directory('directors')
                    ->imageEditor()
                    ->imageAspectRatio('1:1')
                    ->automaticallyOpenImageEditorForAspectRatio()
                    ->automaticallyCropImagesToAspectRatio()
                    ->automaticallyResizeImagesMode('cover')
                    ->image(),
                ColorPicker::make('primary_color'),
                ColorPicker::make('secondary_color'),
            ]),
            Section::make('Redes Sociais')->schema([
                TextInput::make('social_twitter'),
                TextInput::make('social_instagram'),
                TextInput::make('social_github'),
                TextInput::make('social_linkedin'),
            ]),
            Section::make('SEO Global')->schema([
                TextInput::make('default_meta_title'),
                Textarea::make('default_meta_description'),
                FileUpload::make('default_og_image')
                    ->disk('public')
                    ->visibility('public')
                    ->directory('directors')
                    ->imageEditor()
                    ->imageAspectRatio('40:21')
                    ->automaticallyOpenImageEditorForAspectRatio()
                    ->automaticallyCropImagesToAspectRatio()
                    ->automaticallyResizeImagesMode('cover')
                    ->image(),
            ]),
            Section::make('Integrações')->schema([
                TextInput::make('smtp_host'),
                TextInput::make('smtp_port'),
                TextInput::make('recaptcha_key'),
                TextInput::make('recaptcha_secret'),
                Textarea::make('analytics_scripts')
                    ->rows(5),
            ]),
        ]);
    }
}
