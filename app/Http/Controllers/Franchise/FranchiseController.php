<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FranchiseController extends Controller
{
    /**
     * Show order details for franchise.
     */
    public function showOrder($token)
    {
        $orderId = Order::decryptOrderId($token);
        if (!$orderId) abort(404);

        $order = Order::with(['items', 'address', 'user'])->findOrFail($orderId);
        
        // Ensure this order involves one of their products
        $myProductIds = Product::where('user_id', Auth::id())->pluck('id');
        $hasMyProducts = $order->items->whereIn('product_id', $myProductIds->toArray())->isNotEmpty();
        
        // Or if it's their own customer? For now, if it involves their products.
        if (!$hasMyProducts && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        return view('employee.orders.show', compact('order')); // Reusing the view as it's neutral
    }

    /**
     * Display the Franchise Dashboard with wholesale stats
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Find all artifacts belonging to this franchise
        $myProductIds = Product::where('user_id', $user->id)->pluck('id');

        // Calculate Revenue from Sales of their artifacts
        $salesVolume = \App\Models\OrderItem::whereIn('product_id', $myProductIds)
            ->whereHas('order', function($q) {
                $q->where('payment_status', 'Paid');
            })
            ->selectRaw('SUM(quantity * price) as revenue')
            ->first()->revenue ?? 0;

        // Franchise-specific stats
        $stats = [
            'total_volume' => $salesVolume,
            'orders_count' => Order::where('user_id', $user->id)->count(),
            'pending_shipments' => Order::where('user_id', $user->id)->where('status', 'Processing')->count(),
            'inventory_size' => $myProductIds->count(),
        ];

        // Recent orders involving their artifacts or placed by them?
        // Let's go with orders involving their products for clarity
        $recentOrders = Order::whereHas('items', function($q) use ($myProductIds) {
                                $q->whereIn('product_id', $myProductIds);
                             })
                             ->orderBy('ordered_date', 'desc')
                             ->take(5)
                             ->get();

        $activeBroadcasts = \App\Models\GlobalBroadcast::where('is_active', true)
                                ->whereIn('target_audience', ['all', 'exact:franchise'])
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('franchise.dashboard', compact('stats', 'recentOrders', 'activeBroadcasts'));
    }

    /**
     * Wholesale Catalog - Potentially different pricing here later
     */
    public function catalog()
    {
        $products = Product::where('listed_status', 'Listed')->whereNull('user_id')->paginate(12);
        return view('franchise.catalog', compact('products'));
    }

    /**
     * Manage Franchise's own inventory
     */
    public function inventory()
    {
        $products = Product::where('user_id', Auth::id())->paginate(10);
        return view('franchise.inventory.list', compact('products'));
    }

    /**
     * Show form to upload new product
     */
    public function createProduct()
    {
        $categories = \App\Models\Category::all();
        return view('franchise.inventory.create', compact('categories'));
    }

    /**
     * Store new franchise product
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'mrp' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'short_description' => 'required|string',
            'full_description' => 'nullable|string',
            'stock' => 'required|integer',
            'images' => 'required|array|size:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $product = Product::create(array_merge($request->all(), [
            'user_id' => Auth::id(),
            'product_code' => 'BCF-' . strtoupper(\Illuminate\Support\Str::random(6)),
            'listed_status' => 'Draft' // Requires admin final review usually or listed directly
        ]));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $path,
                    'is_main' => ($index === 0)
                ]);
            }
        }

        return redirect()->route('franchise.inventory')->with('success', 'Artifact registered with Sacred Trinity of images.');
    }

    /**
     * Show form to edit artifact
     */
    public function editProduct(Product $product)
    {
        if ($product->user_id !== Auth::id()) abort(403);
        
        $categories = \App\Models\Category::all();
        return view('franchise.inventory.edit', compact('product', 'categories'));
    }

    /**
     * Synchronize artifact updates with the registry
     */
    public function updateProduct(Request $request, Product $product)
    {
        if ($product->user_id !== Auth::id()) abort(403);

        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'mrp' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'short_description' => 'required|string',
            'full_description' => 'nullable|string',
            'stock' => 'required|integer',
            'images' => 'nullable|array|size:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $product->update($request->except('images'));

        if ($request->hasFile('images')) {
            // Remove old Trinity from Registry & Storage
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_url);
                $oldImage->delete();
            }

            // Register New Trinity
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $path,
                    'is_main' => ($index === 0)
                ]);
            }
        }

        return redirect()->route('franchise.inventory')->with('success', 'Artifact alignment synchronized successfully.');
    }

    /**
     * Eradicate artifact from the partner registry
     */
    public function deleteProduct(Product $product)
    {
        if ($product->user_id !== Auth::id()) abort(403);
        
        $product->delete();
        return redirect()->route('franchise.inventory')->with('success', 'Artifact removed from your private collection.');
    }
}
