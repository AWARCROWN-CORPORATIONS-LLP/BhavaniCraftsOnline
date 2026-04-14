<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait InvalidatesHomeCache
{
    /**
     * Boot the trait to listen for model events.
     */
    protected static function bootInvalidatesHomeCache()
    {
        static::saved(fn () => static::clearHomeCache());
        static::deleted(fn () => static::clearHomeCache());
    }

    /**
     * Increment the home cache version to invalidate all cached home page data.
     */
    public static function clearHomeCache()
    {
        if (!Cache::has('home_cache_version')) {
            Cache::put('home_cache_version', 1, now()->addDays(30));
        } else {
            Cache::increment('home_cache_version');
        }
    }
}
