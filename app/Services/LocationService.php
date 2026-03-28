<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LocationService
{
    /**
     * Detect the locale based on the request's IP address.
     */
    public function detectLocale($ip)
    {
        // Skip local IPs
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return config('localization.default_locale', 'en-in');
        }

        return Cache::remember("ip_locale_{$ip}", 86400, function () use ($ip) {
            try {
                // Using ipinfo.io (free tier allows 50k requests/month)
                $response = Http::timeout(2)->get("https://ipinfo.io/{$ip}/json");
                
                if ($response->successful()) {
                    $data = $response->json();
                    $country = strtoupper($data['country'] ?? 'IN');

                    return $this->mapCountryToLocale($country);
                }
            } catch (\Exception $e) {
                // Fallback on error
            }

            return config('localization.default_locale', 'en-in');
        });
    }

    /**
     * Map ISO Country code to our supported locales.
     */
    private function mapCountryToLocale($country)
    {
        $map = [
            'IN' => 'en-in',
            'US' => 'en-us',
            'GB' => 'en-gb',
            'CA' => 'en-us',
            'AU' => 'en-us',
            // Default to India if not specified but we have others
        ];

        return $map[$country] ?? config('localization.default_locale', 'en-in');
    }
}
