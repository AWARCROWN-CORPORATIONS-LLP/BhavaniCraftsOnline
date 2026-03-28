<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class SyncController extends Controller
{
    /**
     * Verify the cached cart and wishlist against the database in the background.
     * This ensures that while the UI is fast, the data is always accurate.
     */
    public function verify()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();
        $cacheKey = $userId ? "user_{$userId}" : "session_{$sessionId}";

        // Verify Cart
        $dbCartTotal = CartItem::where($userId ? 'user_id' : 'session_id', $userId ?: $sessionId)->sum('quantity');
        $cachedCart = Cache::get("cart_{$cacheKey}");
        $cartMismatch = false;
        
        if ($cachedCart) {
            $cachedData = json_decode($cachedCart->getContent(), true);
            if ($cachedData['count'] != $dbCartTotal) {
                $cartMismatch = true;
                Cache::forget("cart_{$cacheKey}"); // Force refresh on next load
            }
        }

        // Verify Wishlist
        $wishlistMismatch = false;
        if ($userId) {
            $dbWishlistTotal = Wishlist::where('user_id', $userId)->count();
            $cachedWishlist = Cache::get("wishlist_{$userId}");
            
            if ($cachedWishlist) {
                $cachedData = json_decode($cachedWishlist->getContent(), true);
                if ($cachedData['count'] != $dbWishlistTotal) {
                    $wishlistMismatch = true;
                    Cache::forget("wishlist_{$userId}"); // Force refresh
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'sync' => [
                'cart_synced' => !$cartMismatch,
                'wishlist_synced' => !$wishlistMismatch,
                'action_required' => ($cartMismatch || $wishlistMismatch)
            ]
        ]);
    }
}
