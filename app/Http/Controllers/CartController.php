<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    public static function mergeCart($oldSessionId, $userId)
    {
        $sessionItems = CartItem::where('session_id', $oldSessionId)->get();
        
        foreach ($sessionItems as $item) {
            $existingItem = CartItem::where('user_id', $userId)
                                    ->where('product_id', $item->product_id)
                                    ->first();
            
            if ($existingItem) {
                $existingItem->quantity += $item->quantity;
                $existingItem->save();
                $item->delete();
            } else {
                $item->user_id = $userId;
                $item->session_id = null;
                $item->save();
            }
        }
    }

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $product = Product::findOrFail($productId);

        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $quantity);
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
        } else {
            $sessionId = Session::getId();
            $cartItem = CartItem::where('session_id', $sessionId)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $quantity);
            } else {
                CartItem::create([
                    'session_id' => $sessionId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
        }

        if ($request->ajax()) {
            // Invalidate Cache
            $cacheKey = Auth::check() ? 'user_'.Auth::id() : 'session_'.Session::getId();
            Cache::forget("cart_{$cacheKey}");

            $cartCount = CartItem::where(Auth::check() ? 'user_id' : 'session_id', Auth::check() ? Auth::id() : Session::getId())->sum('quantity');
            
            return response()->json([
                'message' => 'Artifact added to your ritual cart.',
                'cart_count' => $cartCount
            ]);
        }

        $cacheKey = Auth::check() ? 'user_'.Auth::id() : 'session_'.Session::getId();
        Cache::forget("cart_{$cacheKey}");
        
        return redirect()->back()->with('success', 'Artifact added to your ritual cart.');
    }

    public function index()
    {
        $cacheKey = Auth::check() ? 'user_'.Auth::id() : 'session_'.Session::getId();

        return Cache::remember("cart_{$cacheKey}", now()->addMinutes(30), function () {
            $query = CartItem::with(['product.images', 'product.category']);
            
            if (Auth::check()) {
                $items = $query->where('user_id', Auth::id())->get();
            } else {
                $items = $query->where('session_id', Session::getId())->get();
            }

            $formattedItems = $items->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->product_name,
                    'price' => (float)$item->product->price,
                    'mrp' => (float)$item->product->mrp,
                    'discount_percent' => (int)$item->product->discount_percent,
                    'quantity' => $item->quantity,
                    'image' => ($img = $item->product->images->where('is_main', 1)->first() ?? $item->product->images->first()) 
                                ? \Illuminate\Support\Facades\Storage::url($img->image_url) 
                                : null,
                    'total' => (float)($item->product->price * $item->quantity)
                ];
            });

            return response()->json([
                'items' => $formattedItems,
                'subtotal' => $formattedItems->sum('total'),
                'count' => $items->sum('quantity')
            ]);
        });
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $item = CartItem::findOrFail($request->cart_item_id);
        
        // Security check
        if (Auth::check()) {
            if ($item->user_id !== Auth::id()) return response()->json(['error' => 'Unauthorized'], 403);
        } else {
            if ($item->session_id !== Session::getId()) return response()->json(['error' => 'Unauthorized'], 403);
        }

        $item->update(['quantity' => $request->quantity]);

        $cacheKey = Auth::check() ? 'user_'.Auth::id() : 'session_'.Session::getId();
        Cache::forget("cart_{$cacheKey}");

        return $this->index();
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id'
        ]);

        $item = CartItem::findOrFail($request->cart_item_id);
        
        // Security check
        if (Auth::check()) {
            if ($item->user_id !== Auth::id()) return response()->json(['error' => 'Unauthorized'], 403);
        } else {
            if ($item->session_id !== Session::getId()) return response()->json(['error' => 'Unauthorized'], 403);
        }

        $item->delete();

        $cacheKey = Auth::check() ? 'user_'.Auth::id() : 'session_'.Session::getId();
        Cache::forget("cart_{$cacheKey}");

        return $this->index();
    }

    public function buyNow(Request $request)
    {
        // For Buy Now, we add to cart and redirect to checkout
        $this->add($request);
        return redirect()->route('home', ['locale' => app()->getLocale()]) . '/checkout'; // Directing toward checkout URL structure
    }

    public function buyKit(Request $request)
    {
        $request->validate([
            'ritual_kit_id' => 'required|exists:ritual_kits,id'
        ]);

        $kit = \App\Models\RitualKit::with('products')->findOrFail($request->ritual_kit_id);
        
        foreach ($kit->products as $product) {
            // We simulate the add logic for each product
            $this->addProductToCart($product->id, 1);
        }

        // Invalidate Cache
        $cacheKey = Auth::check() ? 'user_'.Auth::id() : 'session_'.Session::getId();
        Cache::forget("cart_{$cacheKey}");

        return redirect()->route('checkout', ['locale' => app()->getLocale()]);
    }

    private function addProductToCart($productId, $quantity)
    {
        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $quantity);
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
        } else {
            $sessionId = Session::getId();
            $cartItem = CartItem::where('session_id', $sessionId)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $quantity);
            } else {
                CartItem::create([
                    'session_id' => $sessionId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
        }
    }
}
