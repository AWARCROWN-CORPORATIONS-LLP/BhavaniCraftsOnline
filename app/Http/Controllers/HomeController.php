<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class HomeController extends Controller
{
    public function index($locale)
    {
        $page = request()->get('page', 1);
        $version = \Illuminate\Support\Facades\Cache::get('home_cache_version', 1);
        $cacheKey = "home_products_{$locale}_page_{$page}_v{$version}";
        
        $products = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addDays(7), function() {
            return Product::with(['images', 'category'])
                ->where('listed_status', 'Listed')
                ->orderBy('id', 'desc')
                ->paginate(12);
        });

        $pageContent = \Illuminate\Support\Facades\Cache::remember("page_content_global_v{$version}", now()->addDays(7), function() {
            return \App\Models\PageContent::all()->pluck('value', 'key');
        });

        $suggestionService = new \App\Services\SuggestionService();
        $recommendationMode = $pageContent['recommendation_mode'] ?? 'Festive';
        $recommendationCount = (int)($pageContent['recommendation_count'] ?? 8);
        
        $recommendedProducts = \Illuminate\Support\Facades\Cache::remember("recommendations_{$recommendationMode}_{$recommendationCount}_v{$version}", now()->addDays(7), function() use ($suggestionService, $recommendationMode, $recommendationCount) {
            return $suggestionService->getRecommendations($recommendationMode, $recommendationCount);
        });

        $categories = \Illuminate\Support\Facades\Cache::remember("categories_all_v{$version}", now()->addDays(7), function() {
            return \App\Models\Category::all();
        });

        $ritualKits = \Illuminate\Support\Facades\Cache::remember("ritual_kits_active_v{$version}", now()->addDays(7), function() {
            return \App\Models\RitualKit::with('products')->where('is_active', true)->get();
        });

        return view('welcome', compact('products', 'pageContent', 'recommendedProducts', 'categories', 'ritualKits'));
    }
}
