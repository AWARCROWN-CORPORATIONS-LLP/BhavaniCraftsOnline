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
        $items = Wishlist::where('user_id', $user->id)->with('product')->get();
        
        return view('public.collection', compact('user', 'items'));
    }
}
