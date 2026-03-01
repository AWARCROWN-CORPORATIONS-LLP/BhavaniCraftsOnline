<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['images', 'category'])
            ->where('listed_status', 1);

        // ── Text Search ─────────────────────────────────────────────────────
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
            'popular'    => $query->orderBy('stock', 'desc'),   // proxy metric
            default      => $query->orderBy('id', 'desc'),      // newest
        };

        $products = $query->paginate(16)->withQueryString();

        // ── Filter Options (for dropdowns) ───────────────────────────────────
        $categories = Category::whereHas('products', fn ($pq) => $pq->where('listed_status', 1))->get();
        $materials  = Product::where('listed_status', 1)
                        ->whereNotNull('material_type')
                        ->where('material_type', '!=', '')
                        ->distinct()
                        ->pluck('material_type');

        $priceRange = [
            'min' => Product::where('listed_status', 1)->min('price') ?? 0,
            'max' => Product::where('listed_status', 1)->max('price') ?? 10000,
        ];

        return view('public.search', compact(
            'products', 'categories', 'materials', 'priceRange'
        ));
    }
}
