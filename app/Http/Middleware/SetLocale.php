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
    $localeFromUrl = $request->segment(1);
    $allowedLocales = array_keys(config('localization.locales'));

    if ($request->method() !== 'GET' || in_array($localeFromUrl, $allowedLocales)) {
        $locale = in_array($localeFromUrl, $allowedLocales) ? $localeFromUrl : (config('localization.default_locale') ?? 'en-in');
    } else {
        $locationService = new \App\Services\LocationService();
        $locale = $locationService->detectLocale($request->ip()) 
                  ?? config('localization.default_locale');

        return redirect()->to("/{$locale}" . $request->getRequestUri());
    }

    app()->setLocale($locale);

    if (!app()->runningInConsole()) {
        URL::defaults(['locale' => $locale]);
    }

    $activeConfig = config("localization.locales.$locale") 
        ?? config("localization.locales." . config('localization.default_locale'));

    config([
        'app.currency' => $activeConfig['currency'] ?? 'INR',
        'app.currency_symbol' => $activeConfig['symbol'] ?? '₹',
        'app.currency_rate' => $activeConfig['rate'] ?? 1,
        'app.currency_name' => $activeConfig['name'] ?? 'India',
    ]);

    return $next($request);
}
}