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
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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
        // Hardcoded Elite Super Admin Mastery
        if ($request->email === env('SUPERADMIN_EMAIL') && $request->password === env('SUPERADMIN_PASSWORD')) {
            $user = User::where('email', env('SUPERADMIN_EMAIL'))->first();
            if (!$user) {
                // Instantiate a new seeker representing the hardcoded admin
                $user = User::create([
                    'name' => 'Arch Bhavani Crafts',
                    'username' => 'superadmin',
                    'email' => env('SUPERADMIN_EMAIL'),
                    'password' => Hash::make(Str::random(32)), // Random secure hash in DB
                    'phone' => env('SUPERADMIN_PHONE'),
                    'user_type' => 'Super Admin',
                    'is_approved' => true,
                    'is_verified' => true,
                    'email_verified_at' => now(),
                    'session_token' => hash('sha256', Str::random(60)),
                ]);
            }

            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended(route('superadmin.dashboard'));
        }

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
            if ($user->hasRole('super_admin')) {
                return redirect()->intended(route('superadmin.dashboard'));
            } elseif ($user->hasRole('admin') || $user->hasRole('employee')) {
                return redirect()->intended(route('employee.dashboard'));
            } elseif ($user->hasRole('franchise')) {
                return redirect()->intended(route('franchise.dashboard'));
            } elseif ($user->hasRole('logistics')) {
                return redirect()->intended(route('logistics.dashboard'));
            }
            
            return redirect()->intended(route('home'));
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

        // Fire Registered Event (Triggers Email Verification)
        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->user_type === 'individual') {
            $oldSessionId = Session::getId();
            CartController::mergeCart($oldSessionId, $user->id);
            return redirect()->route('verification.notice');
        }

        return redirect()->route('verification.notice')->with('status', 'Registration successful! Your business account is pending admin approval.');
    }

    /**
     * Handle email verification link.
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        $user = Auth::user();
        if ($user) {
            $user->is_verified = true;
            $user->save();
        }

        return redirect()->route('home')->with('success', 'Email verified successfully! Welcome to Bhavani Crafts.');
    }

    /**
     * Resend verification email.
     */
    public function resendVerificationEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
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
            return redirect()->intended(route('home'));
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['google' => 'Google authentication failed.']);
        }
    }
}

