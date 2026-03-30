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
        $product = Product::with(['images', 'category', 'user', 'reviews.user', 'ritualKits.products.images'])
            ->where('listed_status', 'Listed')
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::with(['images' => function($q) { $q->where('is_main', true); }])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('listed_status', 'Listed')
            ->take(4)
            ->get();

        return view('public.product_details', compact('product', 'relatedProducts'));
    }
}
