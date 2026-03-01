<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with(['images' => function($query) {
                $query->where('is_main', true);
            }, 'category'])
            ->where('listed_status', 'Listed')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('welcome', compact('products'));
    }
}
