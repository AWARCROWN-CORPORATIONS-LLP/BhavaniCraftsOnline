<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Security Check: Admin approval required for Business/Franchise accounts
            if (!$user->is_approved) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your business account is pending admin approval. You will be notified via SMS/Email once approved.']);
            }

            $oldSessionId = Session::getId();
            
            // Secure Session Token (Anti-Hacker mechanism)
            $user->session_token = hash('sha256', Str::random(60));
            $user->save();

            $request->session()->regenerate();

            // Merge Sacred Selections
            CartController::mergeCart($oldSessionId, $user->id);
            
            // Tiered Portal Redirection
            if ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->hasRole('employee')) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->hasRole('franchise')) {
                return redirect()->intended('/franchise/dashboard');
            }
            
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:15',
            'user_type' => 'required|in:individual,business',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'user_type' => $data['user_type'],
            // Individual user is auto-approved, business needs manual approval
            'is_approved' => $data['user_type'] === 'individual' ? 1 : 0, 
            'session_token' => hash('sha256', Str::random(60)),
        ]);

        // Placeholder for SMS Logic (as requested)
        // $this->sendSms($user->phone, "Welcome to Bhavani Crafts!");

        if ($user->user_type === 'individual') {
            $oldSessionId = Session::getId();
            Auth::login($user);
            $request->session()->regenerate();
            CartController::mergeCart($oldSessionId, $user->id);
            return redirect('/');
        }

        return redirect('/login')->with('status', 'Registration successful! Your business account is pending admin approval.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Google Login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Register new user via Google
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'username' => $googleUser->getNickname() ?? explode('@', $googleUser->getEmail())[0],
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'google_id' => $googleUser->getId(),
                    'is_verified' => true,
                    'session_token' => hash('sha256', Str::random(60)),
                ]);
            } else {
                $user->google_id = $googleUser->getId();
                $user->session_token = hash('sha256', Str::random(60));
                $user->save();
            }

            $oldSessionId = Session::getId();
            Auth::login($user);
            Session::regenerate();
            CartController::mergeCart($oldSessionId, $user->id);
            return redirect()->intended('/');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['google' => 'Google authentication failed.']);
        }
    }
}

