<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TenantController as AdminTenantController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\PaymentSettingsController as AdminPaymentSettingsController;
use App\Http\Controllers\Admin\StripeController as AdminStripeController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| These routes are for the admin panel to manage the SaaS application.
| All routes require admin authentication and permissions.
|
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    
    // Admin Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/metrics', [AdminDashboardController::class, 'getMetrics'])->name('dashboard.metrics');
    Route::get('/dashboard/charts', [AdminDashboardController::class, 'getChartData'])->name('dashboard.charts');

    // User Management
    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/deactivate', [AdminUserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/{user}/send-verification', [AdminUserController::class, 'sendVerification'])->name('users.send-verification');
    Route::get('/users/{user}/login-as', [AdminUserController::class, 'loginAs'])->name('users.login-as');

    // Tenant Management
    Route::resource('tenants', AdminTenantController::class);
    Route::post('/tenants/{tenant}/activate', [AdminTenantController::class, 'activate'])->name('tenants.activate');
    Route::post('/tenants/{tenant}/deactivate', [AdminTenantController::class, 'deactivate'])->name('tenants.deactivate');
    Route::post('/tenants/{tenant}/extend-trial', [AdminTenantController::class, 'extendTrial'])->name('tenants.extend-trial');
    Route::post('/tenants/{tenant}/change-plan', [AdminTenantController::class, 'changePlan'])->name('tenants.change-plan');

    // Plan Management
    Route::resource('plans', AdminPlanController::class);
    Route::post('/plans/{plan}/activate', [AdminPlanController::class, 'activate'])->name('plans.activate');
    Route::post('/plans/{plan}/deactivate', [AdminPlanController::class, 'deactivate'])->name('plans.deactivate');
    Route::post('/plans/{plan}/toggle', [AdminPlanController::class, 'toggle'])->name('plans.toggle');

    // Subscription Management
    Route::resource('subscriptions', AdminSubscriptionController::class);
    Route::post('/subscriptions/{subscription}/activate', [AdminSubscriptionController::class, 'activate'])->name('subscriptions.activate');
    Route::post('/subscriptions/{subscription}/cancel', [AdminSubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('/subscriptions/{subscription}/extend', [AdminSubscriptionController::class, 'extend'])->name('subscriptions.extend');

    // Payment Settings
    Route::get('/payment-settings', [AdminPaymentSettingsController::class, 'index'])->name('payment-settings.index');
    Route::put('/payment-settings', [AdminPaymentSettingsController::class, 'update'])->name('payment-settings.update');
    Route::post('/payment-settings/test', [AdminPaymentSettingsController::class, 'testConnection'])->name('payment-settings.test');

    // Stripe Integration Management
    Route::prefix('stripe')->name('stripe.')->group(function () {
        Route::get('/', [AdminStripeController::class, 'index'])->name('index');
        Route::get('/subscriptions', [AdminStripeController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/invoices', [AdminStripeController::class, 'invoices'])->name('invoices');
        Route::get('/customers', [AdminStripeController::class, 'customers'])->name('customers');
        Route::post('/sync-subscriptions', [AdminStripeController::class, 'syncSubscriptions'])->name('sync-subscriptions');
        Route::post('/refund/{invoice}', [AdminStripeController::class, 'refund'])->name('refund');
        Route::get('/webhook-logs', [AdminStripeController::class, 'webhookLogs'])->name('webhook-logs');
    });

    // Reports and Analytics
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::get('/revenue', [AdminReportController::class, 'revenue'])->name('revenue');
        Route::get('/users', [AdminReportController::class, 'users'])->name('users');
        Route::get('/churn', [AdminReportController::class, 'churn'])->name('churn');
        Route::get('/ltv', [AdminReportController::class, 'ltv'])->name('ltv');
        Route::get('/mrr', [AdminReportController::class, 'mrr'])->name('mrr');
        Route::get('/conversion', [AdminReportController::class, 'conversion'])->name('conversion');
        Route::get('/export/{type}', [AdminReportController::class, 'export'])->name('export');
    });

    // System Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminSettingsController::class, 'index'])->name('index');
        Route::get('/general', [AdminSettingsController::class, 'general'])->name('general');
        Route::put('/general', [AdminSettingsController::class, 'updateGeneral'])->name('general.update');
        Route::get('/email', [AdminSettingsController::class, 'email'])->name('email');
        Route::put('/email', [AdminSettingsController::class, 'updateEmail'])->name('email.update');
        Route::get('/stripe', [AdminSettingsController::class, 'stripe'])->name('stripe');
        Route::put('/stripe', [AdminSettingsController::class, 'updateStripe'])->name('stripe.update');
        Route::get('/features', [AdminSettingsController::class, 'features'])->name('features');
        Route::put('/features', [AdminSettingsController::class, 'updateFeatures'])->name('features.update');
        Route::get('/maintenance', [AdminSettingsController::class, 'maintenance'])->name('maintenance');
        Route::put('/maintenance', [AdminSettingsController::class, 'updateMaintenance'])->name('maintenance.update');
    });

    // System Health and Monitoring
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/health', [AdminDashboardController::class, 'systemHealth'])->name('health');
        Route::get('/logs', [AdminDashboardController::class, 'logs'])->name('logs');
        Route::get('/queue', [AdminDashboardController::class, 'queueStatus'])->name('queue');
        Route::get('/cache', [AdminDashboardController::class, 'cacheStatus'])->name('cache');
        Route::post('/cache/clear', [AdminDashboardController::class, 'clearCache'])->name('cache.clear');
        Route::get('/database', [AdminDashboardController::class, 'databaseStatus'])->name('database');
    });

    // Notifications and Communications
    Route::prefix('communications')->name('communications.')->group(function () {
        Route::get('/notifications', [AdminDashboardController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/send', [AdminDashboardController::class, 'sendNotification'])->name('notifications.send');
        Route::get('/emails', [AdminDashboardController::class, 'emails'])->name('emails');
        Route::post('/emails/send', [AdminDashboardController::class, 'sendEmail'])->name('emails.send');
        Route::get('/announcements', [AdminDashboardController::class, 'announcements'])->name('announcements');
        Route::post('/announcements', [AdminDashboardController::class, 'createAnnouncement'])->name('announcements.store');
    });

    // Support and Help
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/tickets', [AdminDashboardController::class, 'tickets'])->name('tickets');
        Route::get('/tickets/{ticket}', [AdminDashboardController::class, 'showTicket'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply', [AdminDashboardController::class, 'replyTicket'])->name('tickets.reply');
        Route::post('/tickets/{ticket}/close', [AdminDashboardController::class, 'closeTicket'])->name('tickets.close');
    });

    // API Management
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/tokens', [AdminDashboardController::class, 'apiTokens'])->name('tokens');
        Route::post('/tokens', [AdminDashboardController::class, 'createApiToken'])->name('tokens.create');
        Route::delete('/tokens/{token}', [AdminDashboardController::class, 'revokeApiToken'])->name('tokens.revoke');
        Route::get('/usage', [AdminDashboardController::class, 'apiUsage'])->name('usage');
        Route::get('/rate-limits', [AdminDashboardController::class, 'rateLimits'])->name('rate-limits');
    });

    // Import/Export Tools
    Route::prefix('tools')->name('tools.')->group(function () {
        Route::get('/import', [AdminDashboardController::class, 'importTools'])->name('import');
        Route::post('/import/users', [AdminDashboardController::class, 'importUsers'])->name('import.users');
        Route::post('/import/tenants', [AdminDashboardController::class, 'importTenants'])->name('import.tenants');
        Route::get('/export', [AdminDashboardController::class, 'exportTools'])->name('export');
        Route::post('/export/users', [AdminDashboardController::class, 'exportUsers'])->name('export.users');
        Route::post('/export/tenants', [AdminDashboardController::class, 'exportTenants'])->name('export.tenants');
        Route::post('/export/data', [AdminDashboardController::class, 'exportData'])->name('export.data');
    });

    // Backup and Recovery
    Route::prefix('backup')->name('backup.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'backups'])->name('index');
        Route::post('/create', [AdminDashboardController::class, 'createBackup'])->name('create');
        Route::post('/restore/{backup}', [AdminDashboardController::class, 'restoreBackup'])->name('restore');
        Route::delete('/delete/{backup}', [AdminDashboardController::class, 'deleteBackup'])->name('delete');
        Route::get('/download/{backup}', [AdminDashboardController::class, 'downloadBackup'])->name('download');
    });

    // Activity Logs
    Route::prefix('activity')->name('activity.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'activityLogs'])->name('logs');
        Route::get('/user/{user}', [AdminDashboardController::class, 'userActivity'])->name('user');
        Route::get('/tenant/{tenant}', [AdminDashboardController::class, 'tenantActivity'])->name('tenant');
        Route::delete('/clear', [AdminDashboardController::class, 'clearActivityLogs'])->name('clear');
    });
});

// Admin API Routes
Route::prefix('admin/api/v1')->name('admin.api.')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/stats', [AdminDashboardController::class, 'getStats']);
    Route::get('/users', [AdminUserController::class, 'apiIndex']);
    Route::get('/tenants', [AdminTenantController::class, 'apiIndex']);
    Route::get('/revenue-chart', [AdminReportController::class, 'revenueChart']);
    Route::get('/user-growth', [AdminReportController::class, 'userGrowthChart']);
});

// Public Admin Routes (no auth required)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminDashboardController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminDashboardController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminDashboardController::class, 'logout'])->name('logout');
});
