<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RestockRequest;

class AdminRestockController extends Controller
{
    public function index()
    {
        $requests = RestockRequest::with(['franchise', 'product'])
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'shipped') ASC")
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.restock.index', compact('requests'));
    }

    public function update(Request $request, RestockRequest $restock)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,shipped',
            'admin_notes' => 'nullable|string',
        ]);

        // If transitioning to shipped, actually add to their product stock
        if ($restock->status != 'shipped' && $request->status == 'shipped') {
            $restock->product->increment('stock', $restock->requested_quantity);
        }

        $restock->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Restock Request status securely updated.');
    }
}
