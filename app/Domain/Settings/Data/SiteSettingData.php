<?php

namespace App\Domain\Settings\Data;

readonly class SiteSettingData
{
    public function __construct(
        public string $site_name = '',
        public ?string $logo = null,
        public ?string $favicon = null,
        public string $primary_color = '#000000',
        public string $secondary_color = '#ffffff',
        public ?string $social_twitter = null,
        public ?string $social_instagram = null,
        public ?string $social_github = null,
        public ?string $social_linkedin = null,
        public ?string $default_meta_title = null,
        public ?string $default_meta_description = null,
        public ?string $default_og_image = null,
        public ?string $smtp_host = null,
        public ?string $smtp_port = null,
        public ?string $recaptcha_key = null,
        public ?string $recaptcha_secret = null,
        public ?string $analytics_scripts = null,
    ) {}
}
