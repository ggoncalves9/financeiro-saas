<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Revenue;
use App\Models\Expense;
use App\Models\Account;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // User statistics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year)
                                 ->count();

        // User type breakdown
        $pjUsers = User::where('type', 'pj')->count();
        $pfUsers = User::where('type', 'pf')->count();

        // Subscription statistics
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $cancelledSubscriptions = Subscription::where('status', 'cancelled')->count();
        $trialSubscriptions = Subscription::where('status', 'trialing')->count();

        // Revenue statistics (global)
        $totalRevenue = Revenue::sum('amount');
        $thisMonthRevenue = Revenue::whereMonth('date', now()->month)
                                   ->whereYear('date', now()->year)
                                   ->sum('amount');

        // System statistics
        $totalAccounts = Account::count();
        $totalExpenses = Expense::sum('amount');

        // Recent users
        $recentUsers = User::with('subscription')
                          ->latest()
                          ->take(10)
                          ->get();

        // Monthly user registration chart data
        $monthlyRegistrations = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                   ->whereYear('created_at', now()->year)
                                   ->groupBy('month')
                                   ->orderBy('month')
                                   ->get()
                                   ->pluck('count', 'month')
                                   ->toArray();

        // Fill missing months with 0
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($monthlyRegistrations[$i])) {
                $monthlyRegistrations[$i] = 0;
            }
        }
        ksort($monthlyRegistrations);

        $stats = [
            'users' => [
                'total' => $totalUsers,
                'active' => $activeUsers,
                'inactive' => $inactiveUsers,
                'new_this_month' => $newUsersThisMonth,
                'pj' => $pjUsers,
                'pf' => $pfUsers
            ],
            'subscriptions' => [
                'active' => $activeSubscriptions,
                'cancelled' => $cancelledSubscriptions,
                'trial' => $trialSubscriptions
            ],
            'financials' => [
                'total_revenue' => $totalRevenue,
                'month_revenue' => $thisMonthRevenue,
                'total_expenses' => $totalExpenses,
                'total_accounts' => $totalAccounts
            ],
            'charts' => [
                'monthly_registrations' => array_values($monthlyRegistrations)
            ]
        ];

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }

    /**
     * Display users management page.
     */
    public function users(Request $request)
    {
        $query = User::with('subscription');

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('subscription_status')) {
            $query->whereHas('subscription', function($q) use ($request) {
                $q->where('status', $request->subscription_status);
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show specific user details.
     */
    public function showUser(User $user)
    {
        $user->load(['subscription', 'accounts', 'revenues', 'expenses', 'goals']);

        $userStats = [
            'total_accounts' => $user->accounts->count(),
            'total_revenue' => $user->revenues->sum('amount'),
            'total_expenses' => $user->expenses->sum('amount'),
            'total_goals' => $user->goals->count(),
            'last_login' => $user->last_login_at,
            'member_since' => $user->created_at->diffForHumans()
        ];

        return view('admin.users.show', compact('user', 'userStats'));
    }

    /**
     * Update user status.
     */
    public function updateUserStatus(Request $request, User $user)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $user->update([
            'is_active' => $request->is_active
        ]);

        $status = $request->is_active ? 'ativado' : 'desativado';
        
        return back()->with('success', "Usuário {$status} com sucesso!");
    }

    /**
     * Display subscriptions management page.
     */
    public function subscriptions(Request $request)
    {
        $query = Subscription::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('plan')) {
            $query->where('stripe_price', $request->plan);
        }

        $subscriptions = $query->latest()->paginate(20);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Display system settings.
     */
    public function settings()
    {
        $settings = [
            'app_name' => config('app.name'),
            'app_version' => config('app.version', '1.0.0'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug'),
            'mail_driver' => config('mail.default'),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Display system analytics.
     */
    public function analytics()
    {
        // Revenue trends
        $revenueByMonth = Revenue::selectRaw('MONTH(date) as month, SUM(amount) as total')
                                 ->whereYear('date', now()->year)
                                 ->groupBy('month')
                                 ->orderBy('month')
                                 ->get()
                                 ->pluck('total', 'month')
                                 ->toArray();

        // Expense trends
        $expensesByMonth = Expense::selectRaw('MONTH(due_date) as month, SUM(amount) as total')
                                  ->whereYear('due_date', now()->year)
                                  ->groupBy('month')
                                  ->orderBy('month')
                                  ->get()
                                  ->pluck('total', 'month')
                                  ->toArray();

        // User growth
        $userGrowth = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                         ->whereYear('created_at', now()->year)
                         ->groupBy('month')
                         ->orderBy('month')
                         ->get()
                         ->pluck('count', 'month')
                         ->toArray();

        // Fill missing months
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($revenueByMonth[$i])) $revenueByMonth[$i] = 0;
            if (!isset($expensesByMonth[$i])) $expensesByMonth[$i] = 0;
            if (!isset($userGrowth[$i])) $userGrowth[$i] = 0;
        }

        ksort($revenueByMonth);
        ksort($expensesByMonth);
        ksort($userGrowth);

        $analytics = [
            'revenue_by_month' => array_values($revenueByMonth),
            'expenses_by_month' => array_values($expensesByMonth),
            'user_growth' => array_values($userGrowth),
            'months' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']
        ];

        return view('admin.analytics', compact('analytics'));
    }
}
