<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthApiController extends Controller
{
    /**
     * Handle performance-optimized login via AJAX/API
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Try login using email or username
        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginField => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_approved) {
                Auth::logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Your business account is pending admin approval. You will be notified via SMS once it is cleared.'
                ], 403);
            }

            if ($user->is_blocked) {
                Auth::logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Access Denied: Your account has been suspended by the master registry for policy violations.'
                ], 403);
            }

            // Secure session token update
            $user->session_token = hash('sha256', Str::random(60));
            $user->save();

            $request->session()->regenerate();

            // Dynamic Redirect based on Role Hierarchy
            $redirectPath = '/';
            if ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->hasRole('employee')) {
                $redirectPath = '/admin/dashboard';
            } elseif ($user->hasRole('franchise')) {
                $redirectPath = '/franchise/dashboard'; 
            }

            return response()->json([
                'success' => true,
                'message' => 'Access Granted',
                'redirect' => $redirectPath
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Incorrect ID or Master Key.'
        ], 401);
    }

    /**
     * Handle performance-optimized registration via AJAX/API
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:15',
            'user_type' => 'required|in:individual,business',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Registration data invalid',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'user_type' => $request->user_type,
                'is_approved' => $request->user_type === 'individual' ? 1 : 0, 
                'session_token' => hash('sha256', Str::random(60)),
            ]);

            // Assign Dynamic Role from Database
            $roleName = ($request->user_type === 'business') ? 'franchise' : 'customer';
            $role = \App\Models\Role::where('name', $roleName)->first();
            if ($role) {
                $user->roles()->attach($role->id);
            }

            if ($user->user_type === 'individual') {
                Auth::login($user);
                return response()->json([
                    'success' => true,
                    'message' => 'Account Created Successfully',
                    'redirect' => '/'
                ], 201);
            }

            return response()->json([
                'success' => true,
                'message' => 'Registration Received! Business accounts require 24-48h approval.',
                'redirect' => '/login'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error during registration.'
            ], 500);
        }
    }
}
