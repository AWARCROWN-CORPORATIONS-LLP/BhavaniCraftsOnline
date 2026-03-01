<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Please sign in to save artifacts.'], 401);
        }

        $userId = Auth::id();
        $productId = $request->product_id;

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $wishlistCount = Wishlist::where('user_id', $userId)->count();
            return response()->json([
                'status' => 'removed', 
                'message' => 'Removed from collection.',
                'wishlist_count' => $wishlistCount
            ]);
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            $wishlistCount = Wishlist::where('user_id', $userId)->count();
            return response()->json([
                'status' => 'added', 
                'message' => 'Added to your sacred collection.',
                'wishlist_count' => $wishlistCount
            ]);
        }
    }

    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['items' => [], 'count' => 0]);
        }

        $items = Wishlist::with(['product.images', 'product.category'])
        ->where('user_id', Auth::id())
        ->get();

        $formattedItems = $items->map(function($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->product_name,
                'price' => (float)$item->product->price,
                'mrp' => (float)$item->product->mrp,
                'discount_percent' => (int)$item->product->discount_percent,
                'image' => ($img = $item->product->images->where('is_main', 1)->first() ?? $item->product->images->first()) 
                            ? \Illuminate\Support\Facades\Storage::url($img->image_url) 
                            : null,
            ];
        });

        return response()->json([
            'items' => $formattedItems,
            'count' => $items->count()
        ]);
    }
}
