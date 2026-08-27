<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\LaravelLogController;
use App\Http\Controllers\SettingController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Admin Authentication routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Public routes (login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');
        
        // OTP routes
        Route::get('/login/otp', [AuthController::class, 'showOtpForm'])->name('login.otp');
        Route::post('/login/otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('login.otp.post');
        Route::post('/login/otp/resend', [AuthController::class, 'resendOtp'])->middleware('throttle:5,1')->name('login.otp.resend');
    });

    // Protected admin routes
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Resource routes
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);

        // Activity Logs routes
        Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);

        // Laravel Logs routes
        Route::get('laravel-logs', [LaravelLogController::class, 'index'])->name('laravel-logs.index');
        Route::get('laravel-logs/{fileName}', [LaravelLogController::class, 'show'])->name('laravel-logs.show');
        Route::delete('laravel-logs/{fileName}/clear', [LaravelLogController::class, 'clear'])->name('laravel-logs.clear');
        Route::delete('laravel-logs/{fileName}', [LaravelLogController::class, 'destroy'])->name('laravel-logs.destroy');

        // Settings routes
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        // Profile routes
        Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
        Route::put('profile/update', [\App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('profile/check-password', [\App\Http\Controllers\ProfileController::class, 'checkPassword'])->name('profile.check-password');

        // Test Error Pages (only in non-production)
        if (app()->environment(['local', 'staging', 'development'])) {
            Route::get('test-error/{code}', function ($code) {
                $allowedCodes = [404, 500, 403, 419, 503];
                if (!in_array($code, $allowedCodes)) {
                    abort(404, 'Error code not found');
                }
                abort((int)$code, 'Test error page');
            })->name('test.error');
        }
    });
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'web'])->group(function () {
        // [ADMIN_ROUTES_MARKER]
});
