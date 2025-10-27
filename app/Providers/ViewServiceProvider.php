<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share settings data with backend layouts
        View::composer(
            [
                'layouts.part-layouts.header',
                'layouts.part-layouts.sidebar',
            ],
            function ($view) {
                $setting = Setting::first();
                $defaultLogoPath = asset('assets/logo/csirt-logo.png');
                $logoUrl = $setting && $setting->logo && Storage::disk('public')->exists($setting->logo)
                    ? asset('storage/' . $setting->logo)
                    : $defaultLogoPath;

                $view->with('setting', $setting)->with('logoUrl', $logoUrl);
            }
        );

        // Share settings data with frontend layouts
        View::composer(
            [
                'layouts-frontend.part.header',
                'layouts-frontend.part.navbar',
                'layouts-frontend.part.footer',
            ],
            function ($view) {
                $setting = Setting::first();
                $defaultLogoPath = asset('assets/logo/csirt-logo.png');
                $logoUrl = $setting && $setting->logo && Storage::disk('public')->exists($setting->logo)
                    ? asset('storage/' . $setting->logo)
                    : $defaultLogoPath;

                $view->with('setting', $setting)->with('logoUrl', $logoUrl);
            }
        );
    }
}
