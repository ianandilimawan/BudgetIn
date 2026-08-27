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

// Public Home / Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dynamic Sitemap.xml for Search Engines (Google, Bing, etc.)
Route::get('/sitemap.xml', function () {
    $baseUrl = url('/');
    $now = now()->toAtomString();
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    $xml .= '  <url>' . "\n";
    $xml .= '    <loc>' . htmlspecialchars($baseUrl) . '</loc>' . "\n";
    $xml .= '    <lastmod>' . $now . '</lastmod>' . "\n";
    $xml .= '    <changefreq>weekly</changefreq>' . "\n";
    $xml .= '    <priority>1.0</priority>' . "\n";
    $xml .= '  </url>' . "\n";
    $xml .= '  <url>' . "\n";
    $xml .= '    <loc>' . htmlspecialchars(route('admin.login')) . '</loc>' . "\n";
    $xml .= '    <lastmod>' . $now . '</lastmod>' . "\n";
    $xml .= '    <changefreq>monthly</changefreq>' . "\n";
    $xml .= '    <priority>0.6</priority>' . "\n";
    $xml .= '  </url>' . "\n";
    $xml .= '  <url>' . "\n";
    $xml .= '    <loc>' . htmlspecialchars(route('admin.register')) . '</loc>' . "\n";
    $xml .= '    <lastmod>' . $now . '</lastmod>' . "\n";
    $xml .= '    <changefreq>monthly</changefreq>' . "\n";
    $xml .= '    <priority>0.8</priority>' . "\n";
    $xml .= '  </url>' . "\n";
    $xml .= '</urlset>';

    return response($xml, 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');

// Application routes with 'admin.' name prefix
Route::name('admin.')->group(function () {
    // Public Guest routes (login & register)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');

        // Registration routes for Finance
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.post');
        
        // OTP routes
        Route::get('/login/otp', [AuthController::class, 'showOtpForm'])->name('login.otp');
        Route::post('/login/otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('login.otp.post');
        Route::post('/login/otp/resend', [AuthController::class, 'resendOtp'])->middleware('throttle:5,1')->name('login.otp.resend');
    });

    // Protected routes
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Resource routes
        Route::resource('users', UserController::class);
        // Finance Users management
        Route::post('finance_users/{financeUser}/toggle-status', [\App\Http\Controllers\FinanceUserController::class, 'toggleStatus'])->name('finance_users.toggle_status');
        Route::resource('finance_users', \App\Http\Controllers\FinanceUserController::class)->except(['create', 'store', 'show']);
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

        // Category Budget limits
        Route::get('category_budgets/list', [\App\Http\Controllers\CategoryBudgetController::class, 'list'])->name('category_budgets.list');
        Route::post('category_budgets/update', [\App\Http\Controllers\CategoryBudgetController::class, 'updateBudget'])->name('category_budgets.update');

        // Recurring Transactions
        Route::post('recurring_transactions/{recurring_transaction}/toggle-status', [\App\Http\Controllers\RecurringTransactionController::class, 'toggleStatus'])->name('recurring_transactions.toggle_status');
        Route::post('recurring_transactions/{recurring_transaction}/execute-now', [\App\Http\Controllers\RecurringTransactionController::class, 'executeNow'])->name('recurring_transactions.execute_now');
        Route::resource('recurring_transactions', \App\Http\Controllers\RecurringTransactionController::class);

        // Transaction Category routes
        Route::resource('transaction_categories', \App\Http\Controllers\TransactionCategoryController::class);
        // Cash Transaction export route
        Route::get('cash_transactions/export', [\App\Http\Controllers\CashTransactionController::class, 'export'])->name('cash_transactions.export');
        // Cash Transaction routes
        Route::resource('cash_transactions', \App\Http\Controllers\CashTransactionController::class);
        // Cash Account routes
        Route::resource('cash_accounts', \App\Http\Controllers\CashAccountController::class);
        // Cash Account Type master routes (managed directly from Cash Accounts)
        Route::get('cash_account_types/list', [\App\Http\Controllers\CashAccountTypeController::class, 'list'])->name('cash_account_types.list');
        Route::resource('cash_account_types', \App\Http\Controllers\CashAccountTypeController::class)->except(['create', 'show', 'edit']);

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
        // [ADMIN_ROUTES_MARKER]
    });
});
