<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['images', 'category'])
            ->where('listed_status', 'Listed');

        // ── Text Search ──────────────────────────────────────────────────────
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('product_name', 'LIKE', "%{$q}%")
                   ->orWhere('short_description', 'LIKE', "%{$q}%")
                   ->orWhere('telugu_name', 'LIKE', "%{$q}%")
                   ->orWhere('material_type', 'LIKE', "%{$q}%")
                   ->orWhere('festival_use', 'LIKE', "%{$q}%")
                   ->orWhereHas('category', fn ($cq) => $cq->where('name', 'LIKE', "%{$q}%"));
            });
        }

        // ── Redirect if search empty (requested by user) ─────────────────────
        if (!$request->filled('q') && !$request->filled('category') && !$request->filled('material') && !$request->filled('min_price') && !$request->filled('max_price') && !$request->ajax()) {
            // If we are coming from another page and the search is empty, don't proceed to search
            if ($request->header('referer') && !str_contains($request->header('referer'), route('search'))) {
                return redirect()->back()->with('error', 'Please enter a search term or select a category.');
            }
        }

        // ── Category Filter ──────────────────────────────────────────────────
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // ── Material Filter ──────────────────────────────────────────────────
        if ($request->filled('material')) {
            $query->where('material_type', $request->material);
        }

        // ── Price Range Filter ───────────────────────────────────────────────
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // ── In Stock Filter ──────────────────────────────────────────────────
        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // ── Sorting ──────────────────────────────────────────────────────────
        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular'    => $query->orderBy('stock', 'desc'),
            default      => $query->orderBy('id', 'desc'),
        };

        // ── Live Search: return JSON for AJAX / ?format=json ─────────────────
        if ($request->ajax() || $request->get('format') === 'json') {
            $results = $query->limit(8)->get()->map(function ($product) {
                $mainImage = $product->images->firstWhere('is_main', true)
                    ?? $product->images->first();

                return [
                    'id'           => $product->id,
                    'product_name' => $product->product_name,
                    'slug'         => $product->slug,
                    'price'        => (float) $product->price,
                    'mrp'          => (float) $product->mrp,
                    'image_url'    => $mainImage ? Storage::url($mainImage->image_url) : null,
                    'category'     => ['name' => $product->category?->name ?? ''],
                    'stock'        => $product->stock,
                ];
            });

            return response()->json($results);
        }

        // ── Full Page Response ────────────────────────────────────────────────
        $products = $query->paginate(16)->withQueryString();

        $categories = Category::whereHas('products', fn ($pq) => $pq->where('listed_status', 'Listed'))->get();
        $materials  = Product::where('listed_status', 'Listed')
                        ->whereNotNull('material_type')
                        ->where('material_type', '!=', '')
                        ->distinct()
                        ->pluck('material_type');

        $priceRange = [
            'min' => Product::where('listed_status', 'Listed')->min('price') ?? 0,
            'max' => Product::where('listed_status', 'Listed')->max('price') ?? 10000,
        ];

        return view('public.search', compact(
            'products', 'categories', 'materials', 'priceRange'
        ));
    }
}
