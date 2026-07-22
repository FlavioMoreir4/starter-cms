<?php

namespace App\Http\Middleware;

use App\Domain\Settings\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    #[\Override]
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'site' => $this->getSiteSettings(),
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
        ];
    }

    /**
     * Summary of getSiteSettings
     *
     * @return array<string, mixed>
     */
    protected function getSiteSettings(): array
    {
        try {
            /** @var SiteSetting $settings */
            $settings = app(SiteSetting::class);

            return [
                'site_name' => $settings->site_name ?: config('app.name'),
                'logo' => $settings->logo,
                'primary_color' => $settings->primary_color,
            ];
        } catch (\Throwable) {
            return [
                'site_name' => config('app.name'),
                'logo' => null,
                'primary_color' => '#000000',
            ];
        }
    }
}
