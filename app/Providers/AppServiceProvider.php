<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            static $siteLogo;
            static $announcements;
            static $footerBranches;
            static $footerSettings;
            
            if (!$siteLogo) {
                $siteLogo = \App\Models\PageContent::where('key', 'site_logo')->value('value') ?? '/storage/logos/Bhavanicrafts.png';
            }
            if (!isset($announcements)) {
                $announcements = \App\Models\GlobalBroadcast::where('is_active', true)->pluck('message')->toArray();
                if (empty($announcements)) {
                    $announcements = ['Welcome to Bhavani Crafts | Heritage & Divinity in Art'];
                }
            }
            if (!isset($footerBranches)) {
                $footerBranches = \App\Models\Branch::where('is_active', true)->orderBy('sort_order')->get();
            }
            if (!isset($footerSettings)) {
                $footerSettings = \App\Models\PageContent::whereIn('key', [
                    'footer_description',
                    'footer_facebook',
                    'footer_instagram',
                    'footer_pinterest',
                    'footer_youtube',
                    'footer_copyright',
                    'contact_whatsapp_number'
                ])->pluck('value', 'key')->toArray();
            }
            
            $view->with([
                'siteLogo' => $siteLogo,
                'announcements' => $announcements,
                'footerBranches' => $footerBranches,
                'footerSettings' => $footerSettings
            ]);
        });
    }
}
