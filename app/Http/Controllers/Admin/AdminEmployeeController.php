<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminEmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index($locale)
    {
        // Get users with roles 'employee' or 'associate_admin'
        $employees = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['employee', 'associate_admin']);
        })->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.employees.list', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create($locale)
    {
        return view('admin.employees.create');
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store($locale, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'role' => 'required|in:employee,associate_admin',
        ]);

        $targetRole = Role::where('name', $request->role)->first();

        if (!$targetRole) {
            $targetRole = Role::create(['name' => $request->role]);
        }

        $employee = User::create([
            'username' => Str::slug($request->name) . rand(100, 999), // unique username
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_verified' => true,
            'is_approved' => 1,
            'policy' => '1',
        ]);

        $employee->roles()->attach($targetRole->id);

        return redirect()->route('superadmin.employees.index')->with('success', 'Employee credentials generated and access granted.');
    }

    /**
     * Toggle the block status of the given employee.
     */
    public function toggleBlock($locale, User $employee)
    {
        // 🛡️ Safety Registry: Prevent blocking super admins or admins through this route
        if ($employee->hasRole('super_admin') || $employee->hasRole('admin')) {
            if (request()->ajax()) return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            return back()->withErrors(['message' => 'Unauthorized action.']);
        }
        
        $employee->is_blocked = !$employee->is_blocked;
        $employee->save();

        $status = $employee->is_blocked ? 'revoked' : 'restored';

        if (request()->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => "Employee access has been {$status}.",
                'is_blocked' => (bool)$employee->is_blocked
            ]);
        }

        return redirect()->back()->with('success', "Employee access has been {$status}.");
    }

    /**
     * Delete the employee from the registry permanently.
     */
    public function destroy($locale, User $employee)
    {
        // 🛡️ Safety Registry: Prevent deletion of Superadmins or Core Admins
        if ($employee->hasRole('super_admin') || $employee->hasRole('admin')) {
            if (request()->ajax()) return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            return back()->withErrors(['message' => 'Unauthorized action: Core accounts cannot be deleted.']);
        }

        $employee->roles()->detach();
        $employee->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Employee record purged from registry.']);
        }

        return redirect()->route('superadmin.employees.index')->with('success', 'Employee record purged from registry.');
    }
}
