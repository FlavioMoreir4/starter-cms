<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.site_name', '');
        $this->migrator->add('site.logo', null);
        $this->migrator->add('site.favicon', null);
        $this->migrator->add('site.primary_color', '#000000');
        $this->migrator->add('site.secondary_color', '#ffffff');
        $this->migrator->add('site.social_twitter', null);
        $this->migrator->add('site.social_instagram', null);
        $this->migrator->add('site.social_github', null);
        $this->migrator->add('site.social_linkedin', null);
        $this->migrator->add('site.default_meta_title', null);
        $this->migrator->add('site.default_meta_description', null);
        $this->migrator->add('site.default_og_image', null);
        $this->migrator->add('site.smtp_host', null);
        $this->migrator->add('site.smtp_port', null);
        $this->migrator->add('site.recaptcha_key', null);
        $this->migrator->add('site.recaptcha_secret', null);
        $this->migrator->add('site.analytics_scripts', null);
    }

    public function down(): void
    {
        $this->migrator->delete('site.site_name');
        $this->migrator->delete('site.logo');
        $this->migrator->delete('site.favicon');
        $this->migrator->delete('site.primary_color');
        $this->migrator->delete('site.secondary_color');
        $this->migrator->delete('site.social_twitter');
        $this->migrator->delete('site.social_instagram');
        $this->migrator->delete('site.social_github');
        $this->migrator->delete('site.social_linkedin');
        $this->migrator->delete('site.default_meta_title');
        $this->migrator->delete('site.default_meta_description');
        $this->migrator->delete('site.default_og_image');
        $this->migrator->delete('site.smtp_host');
        $this->migrator->delete('site.smtp_port');
        $this->migrator->delete('site.recaptcha_key');
        $this->migrator->delete('site.recaptcha_secret');
        $this->migrator->delete('site.analytics_scripts');
    }
};
