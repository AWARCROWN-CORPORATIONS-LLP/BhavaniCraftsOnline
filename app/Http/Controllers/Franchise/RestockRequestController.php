<?php

namespace App\Http\Controllers\Franchise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestockRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class RestockRequestController extends Controller
{
    public function index()
    {
        $requests = RestockRequest::with('product')
                        ->where('franchise_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        return view('franchise.restock.index', compact('requests'));
    }

    public function create()
    {
        // Get products owned by this franchise
        $products = Product::where('user_id', Auth::id())->where('listed_status', 'Listed')->get();
        return view('franchise.restock.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'requested_quantity' => 'required|integer|min:1',
            'priority' => 'required|in:normal,urgent,critical',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Ensure franchise owns this product
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        RestockRequest::create([
            'franchise_id' => Auth::id(),
            'product_id' => $product->id,
            'current_stock' => $product->stock,
            'requested_quantity' => $request->requested_quantity,
            'priority' => $request->priority,
            'status' => 'pending',
        ]);

        return redirect()->route('franchise.restock.index')->with('success', 'Automated Restock Request dispatched to Headquarters.');
    }
}
