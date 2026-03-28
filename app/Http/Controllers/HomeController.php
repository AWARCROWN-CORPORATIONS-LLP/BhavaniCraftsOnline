<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class HomeController extends Controller
{
    public function index($locale)
    {
        $products = Product::with(['images', 'category'])
            ->where('listed_status', 'Listed')
            ->orderBy('id', 'desc')
            ->paginate(12);

        $pageContent = \App\Models\PageContent::all()->pluck('value', 'key');

        $suggestionService = new \App\Services\SuggestionService();
        $recommendationMode = $pageContent['recommendation_mode'] ?? 'Festive';
        $recommendationCount = (int)($pageContent['recommendation_count'] ?? 8);
        
        $recommendedProducts = $suggestionService->getRecommendations($recommendationMode, $recommendationCount);

        $categories = \App\Models\Category::all();
        $ritualKits = \App\Models\RitualKit::with('products')->where('is_active', true)->get();

        return view('welcome', compact('products', 'pageContent', 'recommendedProducts', 'categories', 'ritualKits'));
    }
}
