<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display the specified artifact details.
     */
    public function show($token)
    {
        $id = Product::decryptId($token);
        if (!$id) abort(404);

        $product = Product::with(['images', 'category', 'user', 'reviews.user'])
            ->where('listed_status', 'Listed')
            ->findOrFail($id);

        $relatedProducts = Product::with(['images' => function($q) { $q->where('is_main', true); }])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('listed_status', 'Listed')
            ->take(4)
            ->get();

        return view('public.product_details', compact('product', 'relatedProducts'));
    }
}
