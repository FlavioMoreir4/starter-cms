
## Task 2: Package Installation (2026-07-21)

### Composer Packages Installed
- ralphjsmit/laravel-seo 1.8.1
- spatie/laravel-medialibrary 11.23.2
- spatie/laravel-sitemap 8.2.0
- spatie/schema-org 4.0.2
- spatie/laravel-permission 8.3.0
- spatie/laravel-settings 3.9.0

### NPM Packages
- shadcn-vue 2.8.0 (installed as devDependency)
- Note: @shadcn-vue/cli does not exist; the package is just `shadcn-vue`

### Vendor Config Publishing
- PermissionServiceProvider: published config/permission.php + migration ✅
- LaravelSettingsServiceProvider: published config/settings.php + migration ✅
- SEOServiceProvider: NO publishable resources via artisan — must copy manually from vendor/ralphjsmit/laravel-seo/config/seo.php and vendor/ralphjsmit/laravel-seo/database/migrations/
- MediaLibraryServiceProvider: NO publishable resources via artisan — must copy manually from vendor/spatie/laravel-medialibrary/database/migrations/create_media_table.php.stub

### Migrations Run
- Permission tables migration ran successfully
- Settings, Media, SEO migrations exist but NOT yet run (per task scope: "just permission tables for now")

### Vite Config
- Already configured with Vue plugin, Tailwind v4 plugin, and Inertia — no changes needed

### Pending Migrations (for next tasks)
- create_settings_table
- create_media_table
- create_seo_table

## Task 8: Filament Portfolio Resource (2026-07-21)

### Filament v5 Conventions
- Resource form: `form(Schema $schema): Schema` — uses `Schema`, not `Form`
- Table: `table(Table $table): Table` — uses `->recordActions([...])` and `->toolbarActions([...])` (v5 API)
- Actions namespace: `Filament\Actions\*` (NOT `Filament\Tables\Actions\*`)
- Navigation icon: `Heroicon::Briefcase` (BackedEnum, not string `'heroicon-o-briefcase'`)
- Navigation group: `protected static string|UnitEnum|null $navigationGroup = 'Portfólio'`
- Section + Grid layout: `Section::make()->schema([...])` with `Grid::make(2)->schema([...])->columnSpanFull()`
- Form field conventions: Use `->label()` explicitly, `->columnSpanFull()` for full-width fields

### Domain-Driven Resource Placement
- Resources placed in `app/Filament/{Domain}/Resources/` (not auto-discovered)
- Must manually register in AdminPanelProvider with `->resources([Resource::class])`
- Auto-discovery only covers `app/Filament/Resources/`

### HasMany Relationship with TagsInput
- `TagsInput` stores as JSON array, but `Technologies` is a HasMany model
- Solution: TagsInput for form UI + `afterStateHydrated` to load existing + `afterCreate`/`afterSave` hooks to sync
- Technologies are created/deleted on every save (clear + re-create pattern)

### SEO (ralphjsmit/laravel-seo) with Filament
- `HasSEO` trait provides `seo()` morphOne relationship
- Form fields use `seo_title`, `seo_description`, `seo_image` prefix pattern
- Hydration: `->default(fn ($record) => $record?->seo?->title)`
- Save: `$record->seo()->create(...)` on create, `$record->seo()->updateOrCreate([], ...)` on edit

### Spatie Media Library Gallery
- FileUpload with `->multiple()->image()->disk('public')->directory('projects/gallery')`
- Gallery sync: `clearMediaCollection('gallery')` + `addMediaFromDisk($path, 'public')` per file
- Visibility: always set `->visibility('public')` explicitly per Filament guidelines

## Task 12 - Public Controllers & Routes

- Models live in Domain layer: `App\Domain\{Domain}\Models\{Model}` (Content, Portfolio, Pages, Forms)
- Post uses Spatie MediaLibrary + HasSEO, softDeletes; scopePublished checks status + published_at
- Project uses HasMany Technology (not BelongsToMany); scopePublished only checks status
- Page has scopeHomepage and scopePublished; content is JSON cast array
- Form has is_active boolean gate; schema is JSON
- `project_technologies` is NOT a pivot table — has `id`, `project_id`, `name` columns
- Intelephense has indexing issues with `Public` as namespace segment — artisan confirms routes work
- Controllers placed in `app/Http/Controllers/Public/` namespace
- Resources placed in `app/Http/Resources/` namespace
