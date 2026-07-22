<?php

namespace App\Domain\Settings\Actions;

use App\Domain\Settings\Data\SiteSettingData;
use App\Domain\Settings\Models\SiteSetting;

class GetSiteSettingsAction
{
    public static function run(): SiteSettingData
    {
        $settings = app(SiteSetting::class);

        return new SiteSettingData(
            site_name: $settings->site_name,
            logo: $settings->logo,
            favicon: $settings->favicon,
            primary_color: $settings->primary_color,
            secondary_color: $settings->secondary_color,
            social_twitter: $settings->social_twitter,
            social_instagram: $settings->social_instagram,
            social_github: $settings->social_github,
            social_linkedin: $settings->social_linkedin,
            default_meta_title: $settings->default_meta_title,
            default_meta_description: $settings->default_meta_description,
            default_og_image: $settings->default_og_image,
            smtp_host: $settings->smtp_host,
            smtp_port: $settings->smtp_port,
            recaptcha_key: $settings->recaptcha_key,
            recaptcha_secret: $settings->recaptcha_secret,
            analytics_scripts: $settings->analytics_scripts,
        );
    }
}
