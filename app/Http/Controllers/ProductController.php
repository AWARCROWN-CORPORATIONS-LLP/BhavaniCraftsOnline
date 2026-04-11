<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display the specified artifact details.
     */
    public function show($locale, $slug)
    {
        $product = \Illuminate\Support\Facades\Cache::remember("product_detail_{$locale}_{$slug}", now()->addHour(), function() use ($slug) {
            return Product::with(['images', 'category', 'user', 'reviews.user', 'ritualKits.products.images'])
                ->where('listed_status', 'Listed')
                ->where('slug', $slug)
                ->firstOrFail();
        });

        $relatedProducts = \Illuminate\Support\Facades\Cache::remember("product_related_{$product->id}", now()->addHours(6), function() use ($product) {
            return Product::with(['images' => function($q) { $q->where('is_main', true); }])
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('listed_status', 'Listed')
                ->take(4)
                ->get();
        });

        $reviewStats = [
            'total' => $product->reviews->count(),
            'average' => round($product->reviews->avg('rating') ?: 0, 1),
            'distribution' => [
                5 => ['count' => 0, 'percent' => 0],
                4 => ['count' => 0, 'percent' => 0],
                3 => ['count' => 0, 'percent' => 0],
                2 => ['count' => 0, 'percent' => 0],
                1 => ['count' => 0, 'percent' => 0],
            ]
        ];

        if ($reviewStats['total'] > 0) {
            foreach($product->reviews as $rev) {
                if(isset($reviewStats['distribution'][$rev->rating])) {
                    $reviewStats['distribution'][$rev->rating]['count']++;
                }
            }
            foreach([5,4,3,2,1] as $star) {
                $reviewStats['distribution'][$star]['percent'] = ($reviewStats['distribution'][$star]['count'] / $reviewStats['total']) * 100;
            }
        }

        return view('public.product_details', compact('product', 'relatedProducts', 'reviewStats'));
    }
}
