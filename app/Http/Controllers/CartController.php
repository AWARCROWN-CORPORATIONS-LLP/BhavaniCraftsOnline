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
            
            /** @var \App\Models\CartItem $item */
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

    public function add($locale, Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $this->addProductToCart($productId, $quantity);

        if ($request->ajax()) {
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

    public function index($locale)
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

    public function update($locale, Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $item = CartItem::findOrFail($request->cart_item_id);
        
        if (Auth::check()) {
            if ($item->user_id !== Auth::id()) return response()->json(['error' => 'Unauthorized'], 403);
        } else {
            if ($item->session_id !== Session::getId()) return response()->json(['error' => 'Unauthorized'], 403);
        }

        $item->update(['quantity' => $request->quantity]);

        $cacheKey = Auth::check() ? 'user_'.Auth::id() : 'session_'.Session::getId();
        Cache::forget("cart_{$cacheKey}");

        return $this->index($locale);
    }

    public function remove($locale, Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id'
        ]);

        $item = CartItem::findOrFail($request->cart_item_id);
        
        if (Auth::check()) {
            if ($item->user_id !== Auth::id()) return response()->json(['error' => 'Unauthorized'], 403);
        } else {
            if ($item->session_id !== Session::getId()) return response()->json(['error' => 'Unauthorized'], 403);
        }

        $item->delete();

        $cacheKey = Auth::check() ? 'user_'.Auth::id() : 'session_'.Session::getId();
        Cache::forget("cart_{$cacheKey}");

        return $this->index($locale);
    }

    public function buyNow($locale, Request $request)
    {
        $this->add($locale, $request);
        return redirect()->route('checkout', ['locale' => $locale]);
    }

    public function buyKit($locale, Request $request)
    {
        $request->validate([
            'ritual_kit_id' => 'required|exists:ritual_kits,id',
            'product_id' => 'nullable|exists:products,id'
        ]);

        $kit = \App\Models\RitualKit::with('products')->findOrFail($request->ritual_kit_id);
        
        // Add the main product from the page first if provided
        if ($request->has('product_id')) {
            $this->addProductToCart($request->product_id, $request->input('quantity', 1));
        }

        foreach ($kit->products as $product) {
            $this->addProductToCart($product->id, 1);
        }

        $cacheKey = Auth::check() ? 'user_'.Auth::id() : 'session_'.Session::getId();
        Cache::forget("cart_{$cacheKey}");

        return redirect()->route('checkout', ['locale' => $locale]);
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
