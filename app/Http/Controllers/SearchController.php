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
        // ── A10 Base Query Setup ─────────────────────────────────────────────
        $query = Product::with(['images', 'category'])
            ->where('listed_status', 'Listed');

        // ── Text Search & Relevancy ──────────────────────────────────────────
        if ($request->filled('q')) {
            $q = $request->q;
            // Native MySQL Full-Text Search combined with our precalculated A10 Score
            $query->selectRaw("products.*, (MATCH(product_name, short_description, material_type, festival_use) AGAINST(? IN NATURAL LANGUAGE MODE) * 2) + final_a10_score AS a10_relevance_score", [$q])
                  ->where(function ($sq) use ($q) {
                      $sq->whereRaw("MATCH(product_name, short_description, material_type, festival_use) AGAINST(? IN NATURAL LANGUAGE MODE)", [$q])
                         ->orWhere('product_name', 'LIKE', "%{$q}%")
                         ->orWhere('telugu_name', 'LIKE', "%{$q}%")
                         ->orWhereHas('category', fn ($cq) => $cq->where('name', 'LIKE', "%{$q}%"));
                  });
        } else {
            // Default select if no search term, so the query builder remains consistent
            $query->selectRaw("products.*, final_a10_score as a10_relevance_score");
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

        // ── A10 Sorting Logic ────────────────────────────────────────────────
        $sort = $request->get('sort', 'relevance');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular'    => $query->orderBy('stock', 'desc'),
            'newest'     => $query->orderBy('id', 'desc'),
            'relevance'  => $query->orderByRaw('a10_relevance_score DESC, id DESC'),
            default      => $query->orderByRaw('a10_relevance_score DESC, id DESC'),
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

        $categories = \Illuminate\Support\Facades\Cache::remember('search_categories', now()->addHour(), function() {
            return Category::whereHas('products', fn ($pq) => $pq->where('listed_status', 'Listed'))->get();
        });

        $materials = \Illuminate\Support\Facades\Cache::remember('search_materials', now()->addHour(), function() {
            return Product::where('listed_status', 'Listed')
                        ->whereNotNull('material_type')
                        ->where('material_type', '!=', '')
                        ->distinct()
                        ->pluck('material_type');
        });

        $priceRange = \Illuminate\Support\Facades\Cache::remember('search_price_range', now()->addHour(), function() {
            return [
                'min' => Product::where('listed_status', 'Listed')->min('price') ?? 0,
                'max' => Product::where('listed_status', 'Listed')->max('price') ?? 10000,
            ];
        });

        return view('public.search', compact(
            'products', 'categories', 'materials', 'priceRange'
        ));
    }
}
