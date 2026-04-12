<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserLoginNotification;
use App\Mail\UserRegisteredNotification;

class AuthApiController extends Controller
{
    /**
     * Handle performance-optimized login via AJAX/API
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string', // Support Email or Username
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Input Error: Please provide both Email/Username and Password.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Hardcoded Elite Super Admin Mastery
        if ($request->email === env('SUPERADMIN_EMAIL') && $request->password === env('SUPERADMIN_PASSWORD')) {
            $user = User::where('email', env('SUPERADMIN_EMAIL'))->first();
            if (!$user) {
                // Instantiate a new seeker representing the hardcoded admin
                $user = User::create([
                    'name' => 'Arch Bhavani Crafts',
                    'username' => 'superadmin',
                    'email' => env('SUPERADMIN_EMAIL'),
                    'password' => Hash::make(Str::random(32)), 
                    'phone' => env('SUPERADMIN_PHONE'),
                    'user_type' => 'Super Admin',
                    'is_approved' => true,
                    'is_verified' => true,
                    'session_token' => hash('sha256', Str::random(60)),
                ]);
            }

            Auth::login($user);
            $request->session()->regenerate();
            
            return response()->json([
                'success' => true,
                'message' => 'Login successful. Welcome back.',
                'redirect' => route('superadmin.dashboard')
            ], 200);
        }

        try {
            // Try login using email or username
            $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $credentials = [
                $loginField => $request->email,
                'password' => $request->password
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                if ($user->is_blocked) {
                    Auth::logout();
                    return response()->json([
                        'success' => false,
                        'message' => 'Access Denied: Your account has been suspended.'
                    ], 403);
                }

                if (!$user->is_approved) {
                    Auth::logout();
                    return response()->json([
                        'success' => false,
                        'message' => 'Pending Approval: Your business account is still under review.'
                    ], 403);
                }

                // Secure session token update
                $user->session_token = hash('sha256', Str::random(60));
                $user->save();

                // Dispatch Login Notification
                try {
                    Mail::to($user->email)->queue(new UserLoginNotification($user, now()->toDateTimeString(), $request->ip()));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Login Notification Failed: ' . $e->getMessage());
                }

                $request->session()->regenerate();

                // Dynamic Redirect based on Role Hierarchy (Locale Aware)
                $redirectPath = route('home');
                if ($user->hasRole('super_admin')) {
                    $redirectPath = route('superadmin.dashboard');
                } elseif ($user->hasRole('admin') || $user->hasRole('employee')) {
                    $redirectPath = route('employee.dashboard');
                } elseif ($user->hasRole('franchise')) {
                    $redirectPath = route('franchise.dashboard'); 
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Login successful. Welcome back.',
                    'redirect' => $redirectPath
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid email/username or password. Please try again.'
            ], 401);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API Login Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An internal server error occurred.'
            ], 500);
        }
    }

    /**
     * Handle performance-optimized registration via AJAX/API
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|min:3|max:20|alpha_dash|unique:users,username',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|digits:10|unique:users,phone',
            'user_type' => 'required|in:individual,business',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Registration Error: Please check your input fields.',
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

            // Assign Dynamic Role from Database (Case Sensitive Check)
            $roleName = ($request->user_type === 'business') ? 'Franchise' : 'customer';
            $role = \App\Models\Role::where('name', $roleName)->first();
            if ($role) {
                $user->roles()->attach($role->id);
            }

            // Fire Registered Event & Manual Verification Mail Dispatch
            try {
                event(new Registered($user));
                
                $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                    'verification.verify',
                    now()->addMinutes(120),
                    ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
                );

                \Illuminate\Support\Facades\Mail::to($user->email)->queue(new UserRegisteredNotification($user, $verificationUrl));
            } catch (\Throwable $t) {
                \Illuminate\Support\Facades\Log::error('Email Dispatch Failure: ' . $t->getMessage());
            }

            if ($user->user_type === 'individual') {
                Auth::login($user);
                return response()->json([
                    'success' => true,
                    'message' => 'Account created successfully! Please verify your email.',
                    'redirect' => route('verification.notice')
                ], 201);
            }

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Business accounts require approval. Please verify your email.',
                'redirect' => route('verification.notice')
            ], 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('API Register Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to register your details.'
            ], 500);
        }
    }
    /**
     * Send OTP via Fast2SMS
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Phone number is required.'], 422);
        }

        $phone = $request->phone;
        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(10);

        // Store OTP
        \App\Models\OtpVerification::create([
            'phone' => $phone,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'verified' => false
        ]);

        // Send SMS
        $smsService = new \App\Services\SmsService();
        $sent = $smsService->sendOtp($phone, $otp);

        if ($sent) {
            return response()->json(['success' => true, 'message' => 'OTP has been sent to your mobile.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to send OTP. Please try again.'], 500);
    }

    /**
     * Verify OTP and move to the next stage
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:15',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Valid phone and 6-digit OTP required.', 'errors' => $validator->errors()], 422);
        }

        $otpRecord = \App\Models\OtpVerification::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->where('verified', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return response()->json(['success' => false, 'message' => 'The OTP is invalid or has expired.'], 401);
        }

        $otpRecord->verified = true;
        $otpRecord->save();

        return response()->json(['success' => true, 'message' => 'OTP verified. Proceeding...']);
    }

    /**
     * Combined Login & Registration Endpoint (Simplified)
     */
    /**
     * Final Login with Phone & OTP
     */
    public function loginWithOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:15',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Phone and OTP required.'], 422);
        }

        // Verify OTP was actually verified in the table (extra security)
        $otpRecord = \App\Models\OtpVerification::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->where('verified', true)
            ->latest()
            ->first();

        if (!$otpRecord) {
            return response()->json(['success' => false, 'message' => 'The OTP has not been verified.'], 401);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'No account found with this phone number. Please register first.'], 404);
        }

        if (!$user->is_approved) {
            return response()->json(['success' => false, 'message' => 'Your account is pending admin approval.'], 403);
        }

        if ($user->is_blocked) {
             return response()->json(['success' => false, 'message' => 'This account has been suspended.'], 403);
        }

        // Access Granted
        Auth::login($user);
        $user->session_token = hash('sha256', Str::random(60));
        $user->save();
        $request->session()->regenerate();

        $redirectPath = '/';
        if ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->hasRole('employee')) {
            $redirectPath = '/admin/dashboard';
        } elseif ($user->hasRole('franchise')) {
            $redirectPath = '/franchise/dashboard'; 
        }

        return response()->json([
            'success' => true,
            'message' => 'Success! Logged in via OTP.',
            'redirect' => $redirectPath
        ], 200);
    }

    /**
     * Real-time Username Availability Check
     */
    public function checkUsername(Request $request)
    {
        $username = $request->query('username');
        if (!$username || strlen($username) < 3) {
            return response()->json(['available' => false, 'message' => 'Username too short.']);
        }

        $exists = User::where('username', $username)->exists();
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Username is already taken.' : 'Username is available.'
        ]);
    }
}
