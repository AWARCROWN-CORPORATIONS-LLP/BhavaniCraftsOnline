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
    public function store(Request $request, $locale, $slug)
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
            if ($request->header('X-Requested-With') === 'XMLHttpRequest' || $request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'You have already shared your wisdom for this artifact.'], 403);
            }
            return back()->with('error', 'You have already shared your wisdom for this artifact.');
        }

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = $request->file('image')->store('reviews', 'public');
        }

        $review = ProductReview::create([
            'product_id' => $productId,
            'user_id'    => Auth::id(),
            'rating'     => $request->rating,
            'comment'    => $request->comment,
            'image_url'  => $imageUrl,
        ]);

        if ($request->header('X-Requested-With') === 'XMLHttpRequest' || $request->wantsJson() || $request->ajax()) {
            $review->load('user');
            return response()->json([
                'success' => true,
                'message' => 'Thank you for sanctifying this artifact with your review.',
                'review' => [
                    'id' => $review->id,
                    'user_name' => $review->user->name,
                    'user_initial' => substr($review->user->name, 0, 1),
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'time_ago' => $review->created_at->diffForHumans(),
                    'image_url' => $review->image_url ? \Illuminate\Support\Facades\Storage::url($review->image_url) : null
                ]
            ]);
        }

        return back()->with('success', 'Thank you for sanctifying this artifact with your review.');
    }
}
