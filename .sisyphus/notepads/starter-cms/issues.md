
## Task 2 Issues (2026-07-21)

### MediaLibrary & SEO migrations not publishable via artisan
- `vendor:publish --provider=Spatie\LaravelMediaLibrary\MediaLibraryServiceProvider` returns "No publishable resources"
- `vendor:publish --provider=ralphjsmit\LaravelSeo\SeoServiceProvider` returns "No publishable resources"
- Workaround: manually copy migration stubs from vendor directory
- MediaLibrary: vendor/spatie/laravel-medialibrary/database/migrations/create_media_table.php.stub
- SEO: vendor/ralphjsmit/laravel-seo/database/migrations/create_seo_table.php.stub

### @shadcn-vue/cli does not exist on npm
- The correct package name is `shadcn-vue` (not @shadcn-vue/cli)
- Installed successfully as `shadcn-vue` devDependency
