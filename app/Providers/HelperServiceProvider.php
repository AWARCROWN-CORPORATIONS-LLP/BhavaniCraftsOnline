<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::directive('format_currency_abbr', function ($amount) {
            return "<?php 
                \$value = (float) $amount;
                if (\$value >= 1000000) {
                    echo '₹' . round(\$value / 1000000, 1) . 'M';
                } elseif (\$value >= 1000) {
                    echo '₹' . round(\$value / 1000, 1) . 'K';
                } else {
                    echo '₹' . number_format(\$value, 2);
                } 
            ?>";
        });
    }
}
