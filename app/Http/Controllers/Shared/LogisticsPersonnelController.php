<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LogisticsPersonnelController extends Controller
{
    /**
     * Display a listing of the logistics personnel.
     */
    public function index()
    {
        // Get users with role 'logistics'
        $personnel = User::whereHas('roles', function($q) {
            $q->where('name', 'logistics');
        })->orderBy('created_at', 'desc')->paginate(15);
        
        return view('shared.logistics.personnel.index', compact('personnel'));
    }

    /**
     * Show the form for creating a new logistics personnel account.
     */
    public function create()
    {
        return view('shared.logistics.personnel.create');
    }

    /**
     * Store a newly created logistics personnel in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $logisticsRole = Role::firstOrCreate(['name' => 'logistics']);

        $personnel = User::create([
            'username' => 'logistics_' . Str::slug($request->name) . '_' . rand(1000, 9999),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'user_type' => 'logistics',
            'is_verified' => true,
            'is_approved' => 1,
            'policy' => '1',
            'session_token' => hash('sha256', Str::random(60)),
        ]);

        $personnel->roles()->attach($logisticsRole->id);

        return redirect()->route('shared.logistics.personnel.index')->with('success', 'Logistics Personnel Account established successfully.');
    }

    /**
     * Toggle the block status from the registry.
     */
    public function toggleBlock(User $personnel)
    {
        if ($personnel->hasRole('super_admin') || $personnel->hasRole('admin')) {
            return back()->with('error', 'Unauthorized action on core admin tier.');
        }
        
        $personnel->is_blocked = !$personnel->is_blocked;
        $personnel->save();

        $status = $personnel->is_blocked ? 'suspended' : 're-activated';
        return redirect()->back()->with('success', "Logistics operative has been {$status}.");
    }
}
