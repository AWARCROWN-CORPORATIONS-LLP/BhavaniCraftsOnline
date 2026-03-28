<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wishlist;

class CollectionController extends Controller
{
    public function show($token)
    {
        $user = User::where('wishlist_token', $token)->where('wishlist_public', true)->firstOrFail();
        $items = Wishlist::where('user_id', $user->id)->with(['product', 'contributions'])->get();
        
        return view('public.collection', compact('user', 'items'));
    }

    public function contribute(Request $request, $registry_id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'guest_name' => 'required|string|max:255',
        ]);

        $item = Wishlist::with(['product', 'contributions'])->findOrFail($registry_id);
        
        // Ensure not over-funded
        $remaining = max(0, $item->product->price - $item->total_contributed);
        $amount = min($request->amount, $remaining);

        if ($amount > 0) {
            $item->contributions()->create([
                'guest_name' => $request->guest_name,
                'amount' => $amount,
                'payment_status' => 'Paid', // Simulating successful immediate payment for MVP
                'transaction_id' => 'TXN_' . strtoupper(uniqid()),
            ]);
        }

        return back()->with('success', 'Thank you! Your contribution has been recorded.');
    }
}
