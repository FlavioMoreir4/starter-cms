<?php

namespace App\Domain\Settings\Actions;

use App\Domain\Settings\Models\SiteSetting;

class UpdateSiteSettingsAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function run(array $data): void
    {
        $settings = app(SiteSetting::class);

        foreach ($data as $key => $value) {
            $settings->$key = $value;
        }

        $settings->save();
    }
}
