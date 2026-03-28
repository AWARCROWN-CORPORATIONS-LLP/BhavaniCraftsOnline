<?php

namespace App\Http\Controllers\Poojari;

use App\Http\Controllers\Controller;
use App\Models\PoojariProfile;
use App\Models\PoojariBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PoojariDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->poojariProfile;
        
        // Upcoming confirmed/employee_contacted events
        $upcomingEvents = PoojariBooking::where('poojari_id', $user->id)
            ->whereIn('status', ['confirmed', 'employee_contacted'])
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->get();
            
        return view('poojari.dashboard', compact('profile', 'upcomingEvents'));
    }

    public function editProfile()
    {
        $profile = Auth::user()->poojariProfile ?: new PoojariProfile();
        return view('poojari.profile-edit', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'bio' => 'nullable|string',
            'experience_years' => 'required|integer|min:0',
            'specializations' => 'nullable|string',
            'location' => 'nullable|string',
            'availability' => 'nullable|array',
        ]);

        $user = Auth::user();
        $profile = $user->poojariProfile ?: new PoojariProfile(['user_id' => $user->id]);
        
        if (!$profile->exists) {
            $profile->slug = Str::slug($user->name) . '-' . rand(1000, 9999);
        }

        $profile->fill($request->only(['bio', 'experience_years', 'specializations', 'location', 'availability']));
        $profile->save();

        return redirect()->route('poojari.dashboard')->with('success', 'Profile updated successfully.');
    }
}
