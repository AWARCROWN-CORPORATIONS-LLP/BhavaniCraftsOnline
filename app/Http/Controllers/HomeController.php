<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with(['images' => function($query) {
                $query->where('is_main', true);
            }, 'category'])
            ->where('listed_status', 'Listed')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        $pageContent = \App\Models\PageContent::all()->pluck('value', 'key');

        $suggestionService = new \App\Services\SuggestionService();
        $recommendationMode = $pageContent['recommendation_mode'] ?? 'Festive';
        $recommendationCount = (int)($pageContent['recommendation_count'] ?? 8);
        
        $recommendedProducts = $suggestionService->getRecommendations($recommendationMode, $recommendationCount);

        return view('welcome', compact('products', 'pageContent', 'recommendedProducts'));
    }
}
