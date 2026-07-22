<?php

namespace Database\Seeders;

use App\Domain\Settings\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = app(SiteSetting::class);

        $settings->site_name = 'StarterCms';
        $settings->logo = '/images/logo.png';
        $settings->favicon = '/images/favicon.ico';
        $settings->primary_color = '#3B82F6';
        $settings->secondary_color = '#10B981';
        $settings->default_meta_title = 'StarterCms';
        $settings->default_meta_description = 'CMS completo com Laravel + Filament + Inertia';

        $settings->save();
    }
}
