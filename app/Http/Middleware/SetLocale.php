<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1);

        // Check if the first segment is a locale pattern (e.g., 'en' or 'en-in')
        if ($locale && preg_match('/^[a-z]{2}(-[a-z]{2})?$/i', $locale)) {
            App::setLocale($locale);
            
            // Set the default locale for URL generation
            URL::defaults(['locale' => $locale]);
        } else {
            // Intelligent IP-based Regional Detection
            $locationService = new \App\Services\LocationService();
            $locale = $locationService->detectLocale($request->ip());

            App::setLocale($locale);
            URL::defaults(['locale' => $locale]);
        }

        // --- GLOBALIZATION: CURRENCY SETTINGS ---
        $localesConfig = config('localization.locales', []);
        $activeConfig = $localesConfig[$locale] ?? ($localesConfig[config('localization.default_locale')] ?? []);

        // We can temporarily push the active currency config into the runtime config
        config(['app.currency' => $activeConfig['currency'] ?? 'INR']);
        config(['app.currency_symbol' => $activeConfig['symbol'] ?? '₹']);
        config(['app.currency_rate' => $activeConfig['rate'] ?? 1]);
        config(['app.currency_name' => $activeConfig['name'] ?? 'India']);

        // IMPORTANT: Forget the 'locale' parameter so it doesn't get passed to controllers
        if ($request->route() && $request->route()->hasParameter('locale')) {
            $request->route()->forgetParameter('locale');
        }

        return $next($request);
    }
}
