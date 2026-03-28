<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PoojariProfile;
use App\Models\PoojariBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicPoojariController extends Controller
{
    public function index()
    {
        $poojaris = PoojariProfile::with('user')->where('is_verified', true)->get();
        return view('public.poojari.index', compact('poojaris'));
    }

    public function show($slug)
    {
        $profile = PoojariProfile::with('user')->where('slug', $slug)->firstOrFail();
        return view('public.poojari.show', compact('profile'));
    }

    public function book(Request $request)
    {
        $request->validate([
            'poojari_user_id' => 'required|exists:users,id',
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date|after:today',
            'event_address' => 'required|string',
            'additional_notes' => 'nullable|string',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to book a poojari.');
        }

        $booking = PoojariBooking::create([
            'user_id' => Auth::id(),
            'poojari_id' => $request->poojari_user_id,
            'event_name' => $request->event_name,
            'event_date' => $request->event_date,
            'event_address' => $request->event_address,
            'additional_notes' => $request->additional_notes,
            'status' => 'pending',
        ]);

        // Logic here to notify Admin and Employee
        // For now, simple success message
        return back()->with('success', 'Your booking request has been submitted. Our employee will contact you soon to finalize the ritual details.');
    }
}
