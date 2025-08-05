<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Revenue;
use App\Models\Expense;
use App\Models\Goal;
use App\Models\Account;
use App\Models\Category;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentMonth = $request->get('month', now()->month);
        $currentYear = $request->get('year', now()->year);

        // Basic financial data
        $data = [
            'user' => $user,
            'current_month' => $currentMonth,
            'current_year' => $currentYear,
        ];

        // Get accounts summary
        $data['accounts'] = $user->accounts()->get();
        $data['total_balance'] = $data['accounts']->sum('balance');

        // Total financial summary (all time)
        $totalRevenues = $user->revenues()->where('status', 'confirmed')->sum('amount');
        $totalExpenses = $user->expenses()->where('status', '!=', 'cancelled')->sum('amount');
        $balance = $totalRevenues - $totalExpenses;

        $data['total_revenues'] = $totalRevenues;
        $data['total_expenses'] = $totalExpenses;
        $data['total_balance'] = $balance;

        // Monthly financial summary
        $monthlyRevenues = $user->revenues()
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('status', 'confirmed')
            ->sum('amount');

        $monthlyExpenses = $user->expenses()
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('status', '!=', 'cancelled')
            ->sum('amount');

        $data['monthly_revenues'] = $monthlyRevenues;
        $data['monthly_expenses'] = $monthlyExpenses;
        $data['monthly_balance'] = $monthlyRevenues - $monthlyExpenses;

        // Year-to-date summary
        $ytdRevenues = $user->revenues()
            ->whereYear('date', $currentYear)
            ->where('status', 'confirmed')
            ->sum('amount');

        $ytdExpenses = $user->expenses()
            ->whereYear('date', $currentYear)
            ->where('status', '!=', 'cancelled')
            ->sum('amount');

        $data['ytd_revenues'] = $ytdRevenues;
        $data['ytd_expenses'] = $ytdExpenses;
        $data['ytd_balance'] = $ytdRevenues - $ytdExpenses;

        // Recent transactions
        $data['recent_revenues'] = $user->revenues()
            ->latest('date')
            ->limit(5)
            ->get();

        $data['recent_expenses'] = $user->expenses()
            ->latest('date')
            ->limit(5)
            ->get();

        // Goals summary with detailed metrics
        $goals = $user->goals()->where('status', 'active')->get();
        $data['goals'] = $goals;
        $data['total_goals'] = $goals->count();
        
        // Calculate progress percentage for each goal
        $goals->each(function($goal) {
            $goal->progress_percentage = $goal->target_amount > 0 ? 
                round(($goal->current_amount / $goal->target_amount) * 100, 1) : 0;
        });
        
        $data['goals_achieved'] = $goals->where('progress_percentage', '>=', 100)->count();
        $data['goals_completion_rate'] = $goals->count() > 0 ? round($goals->avg('progress_percentage'), 1) : 0;

        // Goals chart data
        $data['goals_chart_data'] = [
            'labels' => $goals->pluck('title')->toArray(),
            'current' => $goals->map(function($goal) {
                return (float) ($goal->current_amount ?? 0);
            })->toArray(),
            'target' => $goals->map(function($goal) {
                return (float) ($goal->target_amount ?? 0);
            })->toArray(),
        ];

        // Chart data for revenues vs expenses (last 6 months)
        $data['chart_data'] = $this->getChartData($user, $currentYear);

        // Category breakdown for current month (garante formato correto para JS)
        $data['expense_categories'] = $this->getExpenseCategories($user, $currentMonth, $currentYear)
            ->map(function($item) {
                return [
                    'name' => $item->category,
                    'total' => (float) $item->total
                ];
            })->values()->toArray();

        $data['revenue_categories'] = $this->getRevenueCategories($user, $currentMonth, $currentYear)
            ->map(function($item) {
                return [
                    'name' => $item->category,
                    'total' => (float) $item->total
                ];
            })->values()->toArray();

        // Format monetary values
        $data['formatted_total_balance'] = 'R$ ' . number_format($data['total_balance'], 2, ',', '.');
        $data['formatted_monthly_revenues'] = 'R$ ' . number_format($data['monthly_revenues'], 2, ',', '.');
        $data['formatted_monthly_expenses'] = 'R$ ' . number_format($data['monthly_expenses'], 2, ',', '.');
        $data['formatted_monthly_balance'] = 'R$ ' . number_format($data['monthly_balance'], 2, ',', '.');

        // Add accounts and categories for quick modals
        $data['accounts'] = Account::where('user_id', auth()->id())->where('is_active', true)->get();
        $data['categories'] = Category::where('user_id', auth()->id())->get();

        return view('dashboard.index', $data);
    }

    /**
     * Get chart data for revenues vs expenses over time
     */
    private function getChartData($user, $year)
    {
        $months = [];
        $revenues = [];
        $expenses = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create($year, $i, 1)->format('M');
            
            $monthRevenue = $user->revenues()
                ->whereMonth('date', $i)
                ->whereYear('date', $year)
                ->where('status', 'confirmed')
                ->sum('amount');
            
            $monthExpense = $user->expenses()
                ->whereMonth('date', $i)
                ->whereYear('date', $year)
                ->where('status', '!=', 'cancelled')
                ->sum('amount');
            
            $revenues[] = (float) $monthRevenue;
            $expenses[] = (float) $monthExpense;
        }

        return [
            'labels' => $months,
            'revenues' => $revenues,
            'expenses' => $expenses,
        ];
    }

    /**
     * Get expense categories breakdown
     */
    private function getExpenseCategories($user, $month, $year)
    {
        return $user->expenses()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', '!=', 'cancelled')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get revenue categories breakdown
     */
    private function getRevenueCategories($user, $month, $year)
    {
        return $user->revenues()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'confirmed')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
    }
}
