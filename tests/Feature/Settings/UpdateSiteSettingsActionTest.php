<?php

use App\Domain\Settings\Actions\UpdateSiteSettingsAction;
use App\Domain\Settings\Models\SiteSetting;

test('updates site settings values', function () {
    UpdateSiteSettingsAction::run([
        'site_name' => 'Updated CMS',
        'primary_color' => '#ff0000',
    ]);

    $settings = app(SiteSetting::class);

    expect($settings->site_name)->toBe('Updated CMS')
        ->and($settings->primary_color)->toBe('#ff0000');
});

test('preserves other settings when updating partial data', function () {
    $settings = app(SiteSetting::class);
    $settings->site_name = 'Original Name';
    $settings->save();

    UpdateSiteSettingsAction::run([
        'primary_color' => '#ff0000',
    ]);

    $settings = app(SiteSetting::class);

    expect($settings->site_name)->toBe('Original Name')
        ->and($settings->primary_color)->toBe('#ff0000');
});

test('updates nullable fields to null', function () {
    $settings = app(SiteSetting::class);
    $settings->social_github = 'https://github.com/test';
    $settings->save();

    UpdateSiteSettingsAction::run([
        'social_github' => null,
    ]);

    $settings = app(SiteSetting::class);

    expect($settings->social_github)->toBeNull();
});

test('updates multiple fields at once', function () {
    UpdateSiteSettingsAction::run([
        'site_name' => 'Full Update',
        'logo' => '/images/logo.png',
        'favicon' => '/images/favicon.ico',
        'contact_email' => 'test@example.com',
    ]);

    $settings = app(SiteSetting::class);

    expect($settings->site_name)->toBe('Full Update')
        ->and($settings->logo)->toBe('/images/logo.png')
        ->and($settings->favicon)->toBe('/images/favicon.ico');
});
