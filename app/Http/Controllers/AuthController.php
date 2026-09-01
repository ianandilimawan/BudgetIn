<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Mail\LoginOtpMail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $settings = Setting::getSettings();
        return view('admin.auth.login', compact('settings'));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('email', 'remember'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Check if credentials are valid without logging in
        if (Auth::validate($credentials)) {
            $user = User::where('email', $request->email)->first();

            // Check if user is active
            if (!$user->is_active) {
                return back()->withErrors([
                    'email' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi Super Admin untuk mengaktifkan kembali akun Anda.',
                ])->onlyInput('email');
            }

            // Check if OTP is enabled in env
            if (env('ENABLE_OTP_LOGIN', false)) {
                $this->generateAndSendOtp($user);
                
                // Store user id in session for OTP verification
                $request->session()->put('otp_user_id', $user->id);
                $request->session()->put('otp_remember', $remember);

                return redirect()->route('admin.login.otp')->with('success', 'Please check your email for the OTP code.');
            }

            // Standard login if OTP is disabled
            Auth::login($user, $remember);
            $request->session()->regenerate();

            ActivityLogService::logCustom([
                'action' => 'Login',
                'model_type' => User::class,
                'model_id' => $user->id,
                'user_id' => $user->id,
                'description' => 'User logged in to the admin panel.',
                'new_values' => ['ip_address' => $request->ip(), 'user_agent' => $request->userAgent()],
            ]);

            return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        $settings = Setting::getSettings();
        return view('admin.auth.register', compact('settings'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau login.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('name', 'email'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'is_active' => true,
        ]);

        // Assign role finance
        $financeRole = \App\Models\Role::firstOrCreate(['name' => 'finance']);
        $user->assignRole($financeRole);

        // Create default starter cash account
        \App\Models\CashAccount::create([
            'user_id' => $user->id,
            'name' => 'Dompet Kas Utama',
            'type' => 'cash',
            'color' => 'emerald',
            'initial_balance' => 0,
            'is_active' => true,
        ]);

        // Login the newly registered user
        Auth::login($user);
        $request->session()->regenerate();

        ActivityLogService::logCustom([
            'action' => 'Register',
            'model_type' => User::class,
            'model_id' => $user->id,
            'user_id' => $user->id,
            'description' => 'User mendaftar sebagai akun Finance baru.',
            'new_values' => ['ip_address' => $request->ip(), 'user_agent' => $request->userAgent()],
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Selamat datang! Akun Finance Anda berhasil dibuat.');
    }

    public function showOtpForm(Request $request)
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('admin.login');
        }

        $settings = Setting::getSettings();
        return view('admin.auth.verify-otp', compact('settings'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('admin.login')->with('error', 'Session expired. Please login again.');
        }

        $userId = $request->session()->get('otp_user_id');
        $user = User::find($userId);

        if (!$user || !$user->is_active) {
            $request->session()->forget(['otp_user_id', 'otp_remember']);
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi Super Admin untuk mengaktifkan kembali akun Anda.',
            ]);
        }
        $attemptsKey = 'login_otp_attempts_' . $userId;
        $cachedOtp = Cache::get('login_otp_' . $userId);

        $attempts = (int) Cache::get($attemptsKey, 0);

        if ($attempts >= 5) {
            // Invalidate OTP on too many failed attempts
            Cache::forget('login_otp_' . $userId);
            Cache::forget($attemptsKey);
            $request->session()->forget(['otp_user_id', 'otp_remember']);
            return redirect()->route('admin.login')->with('error', 'Too many failed OTP attempts. Please login again.');
        }

        if (!$cachedOtp || !hash_equals((string) $cachedOtp, (string) $request->otp)) {
            Cache::put($attemptsKey, $attempts + 1, now()->addMinutes(5));
            $remaining = 5 - ($attempts + 1);
            return back()->withErrors(['otp' => "Invalid or expired OTP code. ({$remaining} attempts remaining)"]);
        }

        // OTP is correct
        $user = User::find($userId);
        if (!$user || !$user->is_active) {
            Cache::forget('login_otp_' . $userId);
            Cache::forget($attemptsKey);
            $request->session()->forget(['otp_user_id', 'otp_remember']);
            return redirect()->route('admin.login')->with('error', 'Akun Anda tidak aktif atau tidak ditemukan.');
        }

        $remember = $request->session()->get('otp_remember', false);

        Auth::login($user, $remember);
        $request->session()->regenerate();
        
        // Clean up
        Cache::forget('login_otp_' . $userId);
        Cache::forget($attemptsKey);
        Cache::forget('login_otp_cooldown_' . $userId);
        $request->session()->forget(['otp_user_id', 'otp_remember']);

        ActivityLogService::logCustom([
            'action' => 'Login',
            'model_type' => User::class,
            'model_id' => $user->id,
            'user_id' => $user->id,
            'description' => 'User logged in to the admin panel via OTP.',
            'new_values' => ['ip_address' => $request->ip(), 'user_agent' => $request->userAgent()],
        ]);

        return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back!');
    }

    public function resendOtp(Request $request)
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('admin.login')->with('error', 'Session expired. Please login again.');
        }

        $userId = $request->session()->get('otp_user_id');

        // 60-second cooldown protection against email flooding
        if (Cache::has('login_otp_cooldown_' . $userId)) {
            return back()->with('error', 'Please wait 60 seconds before requesting another OTP code.');
        }

        $user = User::find($userId);
        $this->generateAndSendOtp($user);

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    private function generateAndSendOtp(User $user)
    {
        $cacheKey = 'login_otp_' . $user->id;
        $cooldownKey = 'login_otp_cooldown_' . $user->id;
        
        // Reuse existing active OTP or generate a new one
        if (Cache::has($cacheKey)) {
            $otp = Cache::get($cacheKey);
        } else {
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // Cache OTP for 5 minutes
            Cache::put($cacheKey, $otp, now()->addMinutes(5));
        }

        // Set 60-second cooldown
        Cache::put($cooldownKey, true, now()->addSeconds(60));

        try {
            Mail::to($user->email)->send(new LoginOtpMail($otp));
        } catch (\Exception $e) {
            // Log error or ignore if mail is not configured yet
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ActivityLogService::logCustom([
                'action' => 'Logout',
                'model_type' => User::class,
                'model_id' => $user->id,
                'user_id' => $user->id,
                'description' => 'User logged out of the admin panel.',
                'new_values' => ['ip_address' => $request->ip(), 'user_agent' => $request->userAgent()],
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }
}
