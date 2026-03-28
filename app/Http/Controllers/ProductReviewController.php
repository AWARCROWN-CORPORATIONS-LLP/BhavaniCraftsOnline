<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $productId = $product->id;

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image'   => 'nullable|image|max:2048', // 2MB limit
        ]);


        // Check if user already reviewed this product (optional but good practice)
        $existing = ProductReview::where('product_id', $productId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already shared your wisdom for this artifact.');
        }

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $request->file('image')->store('reviews', 'public');
        }

        ProductReview::create([
            'product_id' => $productId,
            'user_id'    => Auth::id(),
            'rating'     => $request->rating,
            'comment'    => $request->comment,
            'image_url'  => $imageUrl,
        ]);

        return back()->with('success', 'Thank you for sanctifying this artifact with your review.');
    }
}
