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
    public function index()
    {
        // Get users with role 'employee'
        $employees = User::whereHas('roles', function($q) {
            $q->where('name', 'employee');
        })->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.employees.list', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('admin.employees.create');
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $employeeRole = Role::where('name', 'employee')->firstOrFail();

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

        $employee->roles()->attach($employeeRole->id);

        return redirect()->route('superadmin.employees.index')->with('success', 'Employee credentials generated and access granted.');
    }

    /**
     * Toggle the block status of the given employee.
     */
    public function toggleBlock(User $employee)
    {
        // Prevent blocking super admins or admins through this route just in case
        if ($employee->hasRole('super_admin') || $employee->hasRole('admin')) {
            return back()->withErrors(['message' => 'Unauthorized action.']);
        }
        
        $employee->is_blocked = !$employee->is_blocked;
        $employee->save();

        $status = $employee->is_blocked ? 'revoked' : 'restored';
        return redirect()->back()->with('success', "Employee access has been {$status}.");
    }
}
