<?php

namespace App\Services;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class SuggestionService
{
    /**
     * Get recommended products based on the active mode.
     */
    public function getRecommendations(string $mode = 'Festive', int $limit = 8)
    {
        if ($mode === 'Festive') {
            return $this->getFestiveRecommendations($limit);
        }

        return $this->getHeritageRecommendations($limit);
    }

    /**
     * Algorithm 1: Festive Rush (High Velocity)
     * Focus: Best Sellers + High Discounts + Listed status.
     */
    protected function getFestiveRecommendations(int $limit)
    {
        // Get top selling product IDs from the last 30 days
        $topSellers = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit(50)
            ->pluck('product_id')
            ->toArray();

        // Query products: Prioritize top sellers and high discounts
        $query = Product::where('listed_status', 'Listed')
            ->with(['category', 'images']);

        if (!empty($topSellers)) {
            $idsOrder = implode(',', $topSellers);
            $query->orderByRaw("CASE WHEN id IN ($idsOrder) THEN 0 ELSE 1 END")
                  ->orderBy('discount_percent', 'desc');
        } else {
            // Fallback if no sales yet: Just show high discounts and new arrivals
            $query->orderBy('discount_percent', 'desc')
                  ->orderBy('id', 'desc');
        }

        return $query->take($limit)->get();
    }

    /**
     * Algorithm 2: Artisan's Heritage (Discovery)
     * Focus: Newest Arrivals + Diverse Materials + Cross-Category suggestions.
     */
    protected function getHeritageRecommendations(int $limit)
    {
        // Pick high-quality, newest listings across different categories
        return Product::where('listed_status', 'Listed')
            ->with(['category', 'images'])
            ->orderBy('id', 'desc') // Newest first
            ->inRandomOrder() // Mix it up for discovery
            ->take($limit)
            ->get();
    }
}
