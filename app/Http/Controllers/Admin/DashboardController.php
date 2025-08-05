<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Revenue;
use App\Models\Expense;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = $this->getAdminDashboardData();
        $recentUsers = User::with('subscription')->latest()->take(10)->get();
        $plans = \App\Models\Plan::all();
        
        return view('admin.dashboard.index', compact('stats', 'recentUsers', 'plans'));
    }

    /**
     * Get admin dashboard data.
     */
    private function getAdminDashboardData()
    {
        $now = now();
        
        // User statistics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $newUsersThisMonth = User::whereMonth('created_at', $now->month)
                                ->whereYear('created_at', $now->year)
                                ->count();
        $pjUsers = User::where('type', 'pj')->count();
        $pfUsers = User::where('type', 'pf')->count();

        // Subscription statistics
        $activeSubscriptions = \App\Models\Subscription::where('status', 'active')->count();
        $trialSubscriptions = \App\Models\Subscription::where('status', 'trialing')->count();

        // Financial statistics
        $totalRevenue = \App\Models\Subscription::where('status', 'active')
                                              ->sum('amount') / 100; // Assuming amount is in cents
        $monthlyRevenue = \App\Models\Subscription::where('status', 'active')
                                                 ->whereMonth('created_at', $now->month)
                                                 ->sum('amount') / 100;
        $totalAccounts = \App\Models\Account::count();

        // Monthly registration chart data
        $monthlyRegistrations = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $count = User::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count();
            $monthlyRegistrations[] = $count;
        }

        return [
            'users' => [
                'total' => $totalUsers,
                'active' => $activeUsers,
                'new_this_month' => $newUsersThisMonth,
                'pj' => $pjUsers,
                'pf' => $pfUsers,
            ],
            'subscriptions' => [
                'active' => $activeSubscriptions,
                'trial' => $trialSubscriptions,
            ],
            'financials' => [
                'total_revenue' => $totalRevenue,
                'month_revenue' => $monthlyRevenue,
                'total_accounts' => $totalAccounts,
            ],
            'charts' => [
                'monthly_registrations' => $monthlyRegistrations,
            ],
        ];
    }

    /**
     * Get dashboard metrics via API.
     */
    public function getMetrics()
    {
        $data = $this->getDashboardData();
        
        return response()->json($data);
    }

    /**
     * Get dashboard data.
     */
    private function getDashboardData()
    {
        $now = now();
        
        // Basic counts
        $totalUsers = User::count();
        $totalTenants = Tenant::count();
        $activeUsers = User::where('is_active', true)->count();
        $activeTenants = Tenant::where('is_active', true)->count();

        // New users/tenants this month
        $newUsersThisMonth = User::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();
        
        $newTenantsThisMonth = Tenant::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        // Trial/Subscription statistics
        $trialsActive = Tenant::whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', $now)
            ->count();
        
        $trialsExpiring = Tenant::whereNotNull('trial_ends_at')
            ->whereBetween('trial_ends_at', [$now, $now->copy()->addDays(7)])
            ->count();

        // Plan distribution
        $planDistribution = Tenant::select('plan', DB::raw('count(*) as count'))
            ->groupBy('plan')
            ->get()
            ->keyBy('plan');

        // Monthly Recurring Revenue (MRR) - simplified calculation
        $planPrices = [
            'free' => 0,
            'pro_pf' => 29.90,
            'empresarial' => 99.90,
            'premium_pj' => 299.90
        ];

        $mrr = 0;
        foreach ($planDistribution as $plan => $data) {
            if ($plan !== 'free') {
                $mrr += ($planPrices[$plan] ?? 0) * $data->count;
            }
        }

        // User growth over last 12 months
        $userGrowth = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $count = User::whereYear('created_at', '<=', $date->year)
                ->where(function($query) use ($date) {
                    $query->whereYear('created_at', '<', $date->year)
                        ->orWhere(function($q) use ($date) {
                            $q->whereYear('created_at', $date->year)
                              ->whereMonth('created_at', '<=', $date->month);
                        });
                })
                ->count();
            
            $userGrowth[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        // Recent activities
        $recentUsers = User::latest()->limit(10)->get();
        $recentTenants = Tenant::latest()->limit(10)->get();

        // System health indicators
        $systemHealth = [
            'database' => $this->checkDatabaseHealth(),
            'storage' => $this->checkStorageHealth(),
            'queue' => $this->checkQueueHealth(),
        ];

        // New users today
        $newUsersToday = User::whereDate('created_at', $now->toDateString())->count();
        
        // Revenue calculations (basic)
        $monthlyRevenue = $mrr;
        $revenueGrowth = 10; // Placeholder - implement proper calculation
        
        // Subscription metrics
        $activeSubscribers = $activeTenants;
        $subscriptionRate = $totalUsers > 0 ? ($activeSubscribers / $totalUsers) * 100 : 0;
        
        // Churn metrics (basic calculation)
        $churnRate = 5; // Placeholder - implement proper calculation
        $cancelledThisMonth = 0; // Placeholder

        return [
            'total_users' => $totalUsers,
            'total_tenants' => $totalTenants,
            'active_users' => $activeUsers,
            'active_tenants' => $activeTenants,
            'new_users_this_month' => $newUsersThisMonth,
            'new_users_today' => $newUsersToday,
            'new_tenants_this_month' => $newTenantsThisMonth,
            'trials_active' => $trialsActive,
            'trials_expiring' => $trialsExpiring,
            'plan_distribution' => $planDistribution,
            'mrr' => $mrr,
            'formatted_mrr' => 'R$ ' . number_format($mrr, 2, ',', '.'),
            'monthly_revenue_formatted' => 'R$ ' . number_format($monthlyRevenue, 2, ',', '.'),
            'revenue_growth' => $revenueGrowth,
            'active_subscribers' => $activeSubscribers,
            'subscription_rate' => $subscriptionRate,
            'churn_rate' => $churnRate,
            'cancelled_this_month' => $cancelledThisMonth,
            'user_growth' => $userGrowth,
            'recent_users' => $recentUsers,
            'recent_tenants' => $recentTenants,
            'system_health' => $systemHealth,
        ];
    }

    /**
     * Get chart data for admin dashboard.
     */
    public function getChartData()
    {
        $userGrowthData = $this->getUserGrowthChart();
        $revenueData = $this->getRevenueChart();
        $planDistributionData = $this->getPlanDistributionChart();
        
        return response()->json([
            'user_growth' => $userGrowthData,
            'revenue' => $revenueData,
            'plan_distribution' => $planDistributionData,
        ]);
    }

    /**
     * Get user growth chart data.
     */
    private function getUserGrowthChart()
    {
        $labels = [];
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            $count = User::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $data[] = $count;
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Novos Usuários',
                    'data' => $data,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'tension' => 0.1
                ]
            ]
        ];
    }

    /**
     * Get revenue chart data.
     */
    private function getRevenueChart()
    {
        $labels = [];
        $data = [];
        
        $planPrices = [
            'pro_pf' => 29.90,
            'empresarial' => 99.90,
            'premium_pj' => 299.90
        ];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            $monthlyRevenue = 0;
            foreach ($planPrices as $plan => $price) {
                $count = Tenant::where('plan', $plan)
                    ->where('created_at', '<=', $date->endOfMonth())
                    ->count();
                $monthlyRevenue += $count * $price;
            }
            
            $data[] = $monthlyRevenue;
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Receita Recorrente (MRR)',
                    'data' => $data,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'tension' => 0.1
                ]
            ]
        ];
    }

    /**
     * Get plan distribution chart data.
     */
    private function getPlanDistributionChart()
    {
        $planDistribution = Tenant::select('plan', DB::raw('count(*) as count'))
            ->groupBy('plan')
            ->get();
        
        $labels = [];
        $data = [];
        $colors = [
            'free' => '#6c757d',
            'pro_pf' => '#28a745',
            'empresarial' => '#007bff',
            'premium_pj' => '#ffc107'
        ];
        
        foreach ($planDistribution as $item) {
            $labels[] = ucfirst(str_replace('_', ' ', $item->plan));
            $data[] = $item->count;
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_values($colors),
                    'borderColor' => array_values($colors),
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    /**
     * Check database health.
     */
    private function checkDatabaseHealth()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Conexão com banco de dados OK'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Erro na conexão com banco: ' . $e->getMessage()];
        }
    }

    /**
     * Check storage health.
     */
    private function checkStorageHealth()
    {
        try {
            $diskSpace = disk_free_space(storage_path());
            $totalSpace = disk_total_space(storage_path());
            $usedPercent = (($totalSpace - $diskSpace) / $totalSpace) * 100;
            
            if ($usedPercent > 90) {
                return ['status' => 'warning', 'message' => 'Espaço em disco baixo: ' . round($usedPercent, 2) . '% usado'];
            } elseif ($usedPercent > 95) {
                return ['status' => 'error', 'message' => 'Espaço em disco crítico: ' . round($usedPercent, 2) . '% usado'];
            }
            
            return ['status' => 'healthy', 'message' => 'Espaço em disco OK: ' . round($usedPercent, 2) . '% usado'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Erro ao verificar espaço em disco'];
        }
    }

    /**
     * Check queue health.
     */
    private function checkQueueHealth()
    {
        try {
            // Check if there are failed jobs
            $failedJobs = DB::table('failed_jobs')->count();
            
            if ($failedJobs > 10) {
                return ['status' => 'warning', 'message' => "Muitos jobs falharam: {$failedJobs}"];
            } elseif ($failedJobs > 0) {
                return ['status' => 'warning', 'message' => "Jobs falharam: {$failedJobs}"];
            }
            
            return ['status' => 'healthy', 'message' => 'Fila de jobs OK'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Erro ao verificar fila de jobs'];
        }
    }

    /**
     * Show admin login form.
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if (!$user->is_admin) {
                Auth::logout();
                return back()->withErrors(['email' => 'Acesso não autorizado. Apenas administradores podem acessar esta área.']);
            }
            
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas.',
        ])->onlyInput('email');
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Show system health page.
     */
    public function systemHealth()
    {
        $health = [
            'database' => $this->checkDatabaseHealth(),
            'storage' => $this->checkStorageHealth(),
            'queue' => $this->checkQueueHealth(),
            'cache' => $this->checkCacheHealth(),
            'mail' => $this->checkMailHealth(),
        ];

        return view('admin.system.health', compact('health'));
    }

    /**
     * Check cache health.
     */
    private function checkCacheHealth()
    {
        try {
            cache()->put('health_check', 'ok', 60);
            $result = cache()->get('health_check');
            
            if ($result === 'ok') {
                return ['status' => 'healthy', 'message' => 'Cache funcionando'];
            } else {
                return ['status' => 'error', 'message' => 'Cache não está funcionando'];
            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Erro no cache: ' . $e->getMessage()];
        }
    }

    /**
     * Check mail health.
     */
    private function checkMailHealth()
    {
        try {
            // Simple check if mail configuration exists
            $driver = config('mail.default');
            if (empty($driver)) {
                return ['status' => 'warning', 'message' => 'Configuração de email não definida'];
            }
            
            return ['status' => 'healthy', 'message' => "Email configurado: {$driver}"];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Erro na configuração de email'];
        }
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        try {
            cache()->flush();
            return response()->json(['success' => true, 'message' => 'Cache limpo com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao limpar cache: ' . $e->getMessage()]);
        }
    }
}
