<?php

use App\Domain\Settings\Actions\GetSiteSettingsAction;
use App\Domain\Settings\Data\SiteSettingData;
use App\Domain\Settings\Models\SiteSetting;

test('returns site setting data with defaults', function () {
    $data = GetSiteSettingsAction::run();

    expect($data)->toBeInstanceOf(SiteSettingData::class)
        ->and($data->primary_color)->toBe('#000000')
        ->and($data->secondary_color)->toBe('#ffffff');
});

test('returns updated values after settings change', function () {
    $settings = app(SiteSetting::class);
    $settings->site_name = 'My CMS';
    $settings->social_github = 'https://github.com/test';
    $settings->save();

    $data = GetSiteSettingsAction::run();

    expect($data->site_name)->toBe('My CMS')
        ->and($data->social_github)->toBe('https://github.com/test');
});

test('nullable fields default to null', function () {
    $data = GetSiteSettingsAction::run();

    expect($data->logo)->toBeNull()
        ->and($data->favicon)->toBeNull()
        ->and($data->smtp_host)->toBeNull()
        ->and($data->default_meta_title)->toBeNull()
        ->and($data->analytics_scripts)->toBeNull();
});
