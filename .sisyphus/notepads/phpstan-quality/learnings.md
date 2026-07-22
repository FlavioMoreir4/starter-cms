# PHPStan Quality Task - Learnings

## PHPStan Level 8 Errors
- Most of the 60 errors described in the task were already fixed in prior tasks (19). Only 1 remained: nullable user access in ProfileUpdateRequest.
- Fix: `$this->user()->id` → `$this->user()?->id` (null-safe operator). `profileRules()` already accepts `?int`.

## CarbonImmutable Cast Bug (PHP 8.5)
- **Critical discovery**: Using `CarbonImmutable::class` as a cast value in `$casts` property or `casts()` method causes `UnknownSetterException` in Carbon on PHP 8.5.
- The entire model attributes JSON gets passed as a setter name to a Carbon instance during save.
- **Fix**: Use `'datetime'` string cast instead of `CarbonImmutable::class`. Laravel's `Date::use(CarbonImmutable::class)` in AppServiceProvider already ensures datetime fields resolve to CarbonImmutable.
- This was a pre-existing bug that made ALL Post-related tests fail (15 tests, 9 failures).
- Works: `'datetime'` (string), `null` (no explicit cast)
- Broken: `CarbonImmutable::class`, `Carbon::class`

## Rector Rules (Laravel 13 set)
- `FillablePropertyToFillableAttributeRector` → converts `$fillable` property to `#[Fillable([...])]` attribute
- `AddOverrideAttributeToOverriddenMethodsRector` → adds `#[\Override]` to overridden methods
- `TablePropertyToTableAttributeRector` → converts `$table` property to `#[Table(name: '...')]` attribute
- `ClosureToArrowFunctionRector` → converts single-expression closures to arrow functions
- `DescriptionPropertyToDescriptionAttributeRector` / `SignaturePropertyToSignatureAttributeRector` → converts command properties to attributes
- `FillablePropertyToFillableAttributeRector` only triggers when Rector re-analyzes changed files (incremental)

## Running Tools in Lerd
- `vendor_run` swallows output for failing commands (exit 1). Use `podman exec lerd-php85-fpm sh -c '...'` for full output.
- PHPStan in the FPM container does NOT support `-j` flag (older version). Use `--memory-limit` only.
- The site path must be passed via `path` parameter to lerd tools.

## Pint Conventions
- `fully_qualified_strict_types` rule adds `use` imports for fully-qualified class names used in attributes
- `ordered_` rules reorder use statements
- Run `pint` (not `--test`) on specific files to fix, then `pint --test` to verify
