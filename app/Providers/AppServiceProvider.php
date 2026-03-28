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
            
            if (!$siteLogo) {
                $siteLogo = \App\Models\PageContent::where('key', 'site_logo')->value('value') ?? '/storage/logos/Bhavanicrafts.png';
            }
            if (!isset($announcements)) {
                $announcements = \App\Models\GlobalBroadcast::where('is_active', true)->pluck('message')->toArray();
                if (empty($announcements)) {
                    $announcements = ['Welcome to Bhavani Crafts | Heritage & Divinity in Art'];
                }
            }
            
            $view->with([
                'siteLogo' => $siteLogo,
                'announcements' => $announcements
            ]);
        });
    }
}
