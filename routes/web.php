<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Redirecionar /home para /dashboard
Route::get('/home', function() {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/plans', [\App\Http\Controllers\PlanController::class, 'index'])->name('plans.index');
});
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/insights', [DashboardController::class, 'getInsights'])->name('dashboard.insights');
    Route::get('/dashboard/export', [DashboardController::class, 'exportPdf'])->name('dashboard.export');

    // Profile Management
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [AuthController::class, 'deactivateAccount'])->name('profile.deactivate');

    // Settings
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

    // Two Factor Authentication
    Route::get('/profile/two-factor', [AuthController::class, 'showTwoFactorAuth'])->name('profile.two-factor');
    Route::post('/profile/two-factor', [AuthController::class, 'enableTwoFactorAuth'])->name('profile.two-factor.enable');
    Route::post('/profile/two-factor/confirm', [AuthController::class, 'confirmTwoFactorAuth'])->name('profile.two-factor.confirm');
    Route::delete('/profile/two-factor', [AuthController::class, 'disableTwoFactorAuth'])->name('profile.two-factor.disable');
    Route::post('/profile/two-factor/recovery-codes', [AuthController::class, 'generateRecoveryCodes'])->name('profile.two-factor.recovery-codes');

    // Account Management
    Route::resource('accounts', AccountController::class);
    Route::post('/accounts/{account}/transfer', [AccountController::class, 'transfer'])->name('accounts.transfer');
    Route::post('/accounts/{account}/sync', [AccountController::class, 'sync'])->name('accounts.sync');

    // Revenue Management
    Route::resource('revenues', RevenueController::class);
    Route::post('/revenues/{revenue}/confirm', [RevenueController::class, 'confirm'])->name('revenues.confirm');
    Route::post('/revenues/{revenue}/cancel', [RevenueController::class, 'cancel'])->name('revenues.cancel');
    Route::post('/revenues/{revenue}/duplicate', [RevenueController::class, 'duplicate'])->name('revenues.duplicate');
    Route::get('/revenues-export', [RevenueController::class, 'export'])->name('revenues.export');
    Route::get('/api/revenue-categories', [RevenueController::class, 'getCategories'])->name('api.revenue-categories');
    Route::post('/revenues/quick-store', [RevenueController::class, 'quickStore'])->name('revenues.quick-store');

    // Expense Management
    Route::resource('expenses', ExpenseController::class);
    Route::post('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    Route::post('/expenses/{expense}/pay', [ExpenseController::class, 'markAsPaid'])->name('expenses.pay');
    Route::post('/expenses/{expense}/duplicate', [ExpenseController::class, 'duplicate'])->name('expenses.duplicate');
    Route::get('/expenses-export', [ExpenseController::class, 'export'])->name('expenses.export');
    Route::get('/api/expense-categories', [ExpenseController::class, 'getCategories'])->name('api.expense-categories');
    Route::post('/expenses/quick-store', [ExpenseController::class, 'quickStore'])->name('expenses.quick-store');

    // Goal Management
    Route::resource('goals', GoalController::class);
    Route::post('/goals/{goal}/contribute', [GoalController::class, 'addContribution'])->name('goals.contribute');
    Route::post('/goals/{goal}/withdraw', [GoalController::class, 'withdraw'])->name('goals.withdraw');
    Route::post('/goals/{goal}/complete', [GoalController::class, 'complete'])->name('goals.complete');
    Route::post('/goals/{goal}/pause', [GoalController::class, 'pause'])->name('goals.pause');
    Route::post('/goals/{goal}/resume', [GoalController::class, 'resume'])->name('goals.resume');

    // Category Management
    Route::resource('categories', App\Http\Controllers\CategoryController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/income-statement', [ReportController::class, 'incomeStatement'])->name('reports.income-statement');
    Route::get('/reports/cash-flow', [ReportController::class, 'cashFlow'])->name('reports.cash-flow');
    Route::get('/reports/category-analysis', [ReportController::class, 'categoryAnalysis'])->name('reports.category-analysis');
    Route::get('/reports/goal-progress', [ReportController::class, 'goalProgress'])->name('reports.goal-progress');

    // Team Management (PJ only)
    Route::resource('team', TeamMemberController::class);
    Route::post('/team/{teamMember}/activate', [TeamMemberController::class, 'activate'])->name('team.activate');
    Route::post('/team/{teamMember}/deactivate', [TeamMemberController::class, 'deactivate'])->name('team.deactivate');
    Route::put('/team/{teamMember}/permissions', [TeamMemberController::class, 'updatePermissions'])->name('team.permissions');

    // Business Reports (PJ only)
    Route::middleware('can:view-business-reports')->group(function () {
        Route::get('/reports/dre', [ReportController::class, 'dre'])->name('reports.dre');
        Route::get('/reports/tax-summary', [ReportController::class, 'taxSummary'])->name('reports.tax-summary');
        Route::get('/reports/team-expenses', [ReportController::class, 'teamExpenses'])->name('reports.team-expenses');
    });

    // Subscription Management
    Route::get('/subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');

    // Global Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Public API Routes (for mobile app, integrations)
Route::prefix('api/v1')->middleware(['auth:sanctum', 'verified'])->name('api.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::apiResource('revenues', RevenueController::class)->names('revenues');
    Route::apiResource('expenses', ExpenseController::class)->names('expenses');
    Route::apiResource('goals', GoalController::class)->names('goals');
    Route::apiResource('accounts', AccountController::class)->names('accounts');
});

// Webhook Routes (no authentication)
Route::post('/webhooks/stripe', [WebhookController::class, 'stripe'])->name('webhooks.stripe');
Route::post('/webhooks/bank-sync', [WebhookController::class, 'bankSync'])->name('webhooks.bank-sync');

# Webhook routes (outside auth middleware)
Route::post('/webhook/efi', [WebhookController::class, 'efiPay'])->name('webhook.efi');

# Planos públicos
Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

# Checkout e Pagamentos (requer autenticação)
Route::middleware(['auth', 'verified'])->group(function () {
    # Checkout
    Route::get('/checkout/{plan}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{payment}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/error/{payment?}', [CheckoutController::class, 'error'])->name('checkout.error');
    Route::get('/checkout/pending/{payment}', [CheckoutController::class, 'pending'])->name('checkout.pending');

    # Pagamentos
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::post('/process/pix', [PaymentController::class, 'processPix'])->name('process.pix');
        Route::get('/status/{payment}', [PaymentController::class, 'checkStatus'])->name('check.status');
        Route::get('/history', [PaymentController::class, 'index'])->name('history');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
    });
});

// Health Check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => config('app.version', '1.0.0')
    ]);
})->name('health');
