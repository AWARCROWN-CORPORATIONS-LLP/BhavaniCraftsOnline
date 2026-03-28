<?php

namespace App\Helpers;

class PriceHelper
{
    /**
     * Format a price based on the globally set locale and currency.
     */
    public static function format($price, $includeSymbol = true)
    {
        $rate = config('app.currency_rate', 1);
        $symbol = config('app.currency_symbol', '₹');
        
        $finalPrice = $price * $rate;
        
        // Basic formatting
        $formatted = number_format($finalPrice, $rate == 1 ? 0 : 2);
        
        return $includeSymbol ? $symbol . $formatted : $formatted;
    }
}
